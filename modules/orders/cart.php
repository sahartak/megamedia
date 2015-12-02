<?php
import('session');
import('cart');
import('products');
import('forms');
import('delivery');
import('html');
import('contacts');
import('orders');
import('shipments');
import('documents');
import('mails');
import('html2pdf/html2pdf.class');

// Here the users starts ordering from tan existing offer and we
// load the basket with it
if ((isset($_GET['copy']) && (int)$_GET['copy'] > 0) || (isset($_GET['copy']) && stristr($_GET['copy'], 'Offer') !== false)) {
	if (order_exists($_GET['copy'])) {
		$order = order_get($_GET['copy']);

		if ($order['PARTY_ID'] == $_SESSION['user']['PARTY_ID']) {
			$Cart->fromString($order['SESSION_SERILIALIZE']);
			$Cart->setFromOffer($_GET['copy']);
			$success[] = 'You order is now similar to order %d from %s';
		} else {
			$errors[] = sprintf('Order %d does not belong to you!', $_GET['copy']);
		}
	} else {
		$errors[] = sprintf('Order %d does not exist!', $_GET['copy']);
	}
}

// If we have data coming from the order form
// we save it in the cart
if (isset($_POST) && !empty($_POST)) {

	// When the user has selected an option from the current calculation
	// we add it to the cart to the already existing product calculations
	if (isset($_POST['OFFER']) && isset($_SESSION['CURRENT_CALCULATION'])) {

		// Adding new element to the cart
		$new_cart_elem = array(
			'PRICE' => 0.00,
			'PRODUCTS' => array()
		);

		$new_cart_elem['PRODUCT_CATEGORY_ID'] = $_POST['PRODUCT_CATEGORY_ID'];
		$new_cart_elem['PRODUCTS'] = $_SESSION['CURRENT_CALCULATION'];
		$new_cart_elem['OFFER'] = $_POST['OFFER'];
		foreach ($_SESSION['CURRENT_OFFER']['PRODUCTS'] as $_row) {
			$new_cart_elem['PRICE'] += $_row[$_POST['OFFER']];
		}
		$new_index = $Cart->add($new_cart_elem);
		$Cart->addAddresses($new_index, $_POST['delivery_address']);

		unset($_SESSION['CURRENT_CALCULATION']);
		unset($_SESSION['CURRENT_OFFER']);
		$success = 'Your calculation has been added to your order';

		switch ($_POST['after-add']) {
			case 'continue':
				header('location: /dashboard/dashboard' . '?success=' . $success);
				exit;
				break;
			case 'go-back':
				header('location: /orders/order?PRODUCT_CATEGORY_ID=' . $_POST['PRODUCT_CATEGORY_ID'] . '&success=' . $success);
				exit;
				break;
			case 'add-to-order':
			default:
				// Do nothing as this actions requires us to stay in the
				// cart page to review details for the order
		}
	} else if (isset($_POST['address'])) {
		// Here the cart has been submitted either for being an offer or has been
		// placed as an order
		$is_offer = isset($_POST['offer-state']) && $_POST['offer-state'] == 'true';

		// We need to check if it is order and it
		// is coming from an existing offer then
		// we remove the offer to tbe replaced later
		// with the newly saved order
		if (!$is_offer && $Cart->isFromOffer()) {
			$from_offer_id = $Cart->getOfferId();
			order_delete($from_offer_id);
		}

		// Processing the offer to an order
		// Dealing with delivery addresses
		$requisition_uploaded = false;
		if (!$is_offer) {
			switch ($_POST['address']) {
				case 'alternative':
					$requisition_uploaded = true;

					// If we have selected an old alternative address
					// we do not care about the other fields
					if (trim($_POST['OLD_ALTERNATIVE_CONTACT']) != '') {
						$dest_address = $_POST['OLD_ALTERNATIVE_CONTACT'];
						break;
					}

					// Add the address to the list of the alternative addresses
					foreach ($_POST[ALTERNATIVE_CONTACT] as $_attr => $_val) {
						if ($_attr != CNT_ATTR_COUNTRY) {
							if (trim($_val) == '') {
								$errors[] = 'Please fill in all mandatory fields for the new alernative address!';
								break;
							}
						}
					}

					// If everything has gone OK we add the address to the order
					// shipment record
					if (empty($errors)) {
						$dest_address = contact_add($_SESSION['user']['PARTY_ID'], ALTERNATIVE_CONTACT, $_POST[ALTERNATIVE_CONTACT]);
					}

					break;

				case 'company':
					// Nothing much to do here, if the user has selected company we add
					// the company address to the delivery options
					$company_address = contact_mech_get($_SESSION['user']['PARTY_ID'], COMPANY_CONTACT);
					$dest_address = $company_address['CONTACT_MECH_ID'];
					$requisition_uploaded = true;

					break;

				default:
					$errors[] = 'You have to select address for delivery!';
					break;
			}
		}

		// Checking the additional email
		$additional_email = forms_post('HANDLING_INSTRUCTIONS');
		if (!empty($additional_email)) {
			if (!filter_var($additional_email, FILTER_VALIDATE_EMAIL)) {
				$errors[] = 'The additional email provided is incorrect';
			}
		}

		if (empty($errors)) {
			// Placing the actual order
			$placer_name = sprintf("%s %s", $_SESSION['user']['FIRST_NAME'], $_SESSION['user']['LAST_NAME']);
			$order_id = order_place(ORDER_NORMAL, $_POST['EXTERNAL_ID'], now(), $placer_name, DEFAULT_CURRENCY, $Cart->getTotal(), '', '', '0000-00-00', '', $_POST['COMMENTS'], 0.00, $Cart, $is_offer);

			// Storing Order items
			$order_items = $Cart->getProducts();
			foreach ($order_items as $seq => $_item) {
				foreach ($_item['PRODUCTS']['MOTIVES'] as $index => $_amount) {
					if ($_amount) {

						$unit_list_price = $_item['PRODUCTS']['VARIANT'][$index] . '-' . $_item['OFFER'];
						$feature_price = product_feature_price_get($unit_list_price, CUSTOMER_PRICE, $_SESSION['user']['PARTY_ID']);
						if (empty($feature_price)) {
							$feature_price = product_feature_price_get($unit_list_price, GENERAL_PRICE, '');
						}
						$run_price = product_assoc_get_price($unit_list_price, $_item['OFFER'], $_SESSION['user']['PARTY_ID']);

						order_item_add($order_id, $_item['PRODUCTS']['VARIANT'][$index], '', '', $_amount, $_item['PRICE'], '', $placer_name, 0.00, DEFAULT_CURRENCY, ORDER_NORMAL, $_item['PRODUCTS']['RUNS'][$index], $seq, $_item['OFFER'], $feature_price['PRICE'], $run_price);
					}
				}
			}

			// Adding roles
			order_role_add($order_id, $_SESSION['user']['PARTY_ID'], ORDER_PLACER);
			order_role_add($order_id, $_SESSION['user']['PARTY_ID'], ORDER_CUSTOMER);
			order_role_add($order_id, ENTERPRICE_PARTY_ID, ORDER_RECEIVER);

			// We need to rename the order so we now have the order id
			// instead of the offer id
			if ($from_offer_id && !$is_offer) {
				order_change_id($from_offer_id, $order_id);
			}

			// Creating shipment
			shipment_add(SHIPMENT_TO_CLIENT, $order_id, DEFAULT_CURRENCY, '', '', $dest_address, '', $_SESSION['user']['PARTY_ID'], ENTERPRICE_PARTY_ID, $placer_name, $_POST['HANDLING_INSTRUCTIONS']);

			// Saving files to the server
			if (isset($_FILES) && !$is_offer) {
				foreach ($_FILES['requisition']['name'] as $key => $_requisition) {
					if (!empty($_requisition)) {
						$file_prefix = $_SESSION['CART']['PRODUCTS'][$key]['PRODUCT_CATEGORY_ID'] . $_SESSION['CART']['PRODUCTS'][$key]['OFFER'];
						if (!document_upload($order_id, $file_prefix . '_' . $_FILES['requisition']['name'][$key], $_FILES['requisition']['tmp_name'][$key])) {
							$errors[] = 'Error uploading requisition files. Please contact support';
						}
					}
				}
			}

			// Generating PDF for MM admin and to the customer
			// Generating PDF to be sent to production and the two MM mails
			import('html2pdf/html2pdf.class');
			include ROOT . 'scripts/order-confirmation-pdf.php';
			$customer_pdf = build_customer_pdf($order_id, $_SESSION['user']['PARTY_ID'], true);
			$production_pdf = build_customer_pdf($order_id, $_SESSION['user']['PARTY_ID'], false);
			$order_email = contact_mech_get_attribute(contact_generate_id($_SESSION['user']['PARTY_ID'], ORDER_CONTACTS), CNT_ATTR_EMAIL);
			$affiliate = $affiliate = person_affiliate_get($_SESSION['user']['PARTY_ID']);

			// Sending to client, additional email, profile responsible
			email_send ([$system_settings['ORDER_SAFETY_EMAIL'], $affiliate['AFFILIATE_EMAIL']], 'Megamedia test Production', 'Production Body', $production_pdf);
			email_send ([$order_email, $additional_email, $affiliate['AFFILIATE_EMAIL'], $system_settings['ORDER_SAFETY_EMAIL']], 'Megamedia test Customer', 'Customer Body', $customer_pdf);

			$Cart->reset();
			if (empty($errors)) {
				if ($is_offer) {
					$message = 'Your offer has been saved';
				} else {
					$message = 'Thank you for your order';
				}
				header(sprintf('location: /dashboard/dashboard?success=%s', $message));
				exit;
			} else {
				$success[] = 'Order accepted';
			}
		}
	}
}

// Resets the cart to an empty state
if (isset($_GET['RESET'])) {
	$Cart->reset();
}

// Deletes a calculation from the cart
if (isset($_GET['DELETE'])) {
	$Cart->remove($_GET['DELETE']);
	unset($_GET['DELETE']);
}

// Starts a change sequence for a product
// that has already been calculated and added
// to the cart
if (isset($_GET['CHANGE'])) {
	$products = $Cart->getAt($_GET['CHANGE']);
	if ($products) {
		$post = $products['PRODUCTS'];
		$_SESSION['CURRENT_CALCULATION'] = $post;
		header(sprintf('location: /orders/order?PRODUCT_CATEGORY_ID=%s&recalculate', $products['PRODUCT_CATEGORY_ID']));
		exit;
	} else {
		$errors[] = 'There is no product to edit on this possition';
	}
}

if (!$Cart->getProductsCount()) {
	$errors[] = 'Your order is empty';
}

$contacts = contact_get_by_party($_SESSION['user']['PARTY_ID'], COMPANY_CONTACT);
$alternative = contact_alternative_all($_SESSION['user']['PARTY_ID']);

// If there is an offer attached here then maybe there is some shipment information already existing here
// that we need to display in the form so the customer does not loose their data
if ($Cart->isFromOffer()) {
	$offer_id = $Cart->getOfferId();
	$offer_shipment = shipment_get($offer_id);
	$offer_meta = order_get($offer_id);
}

$template = 'cart';
$link = THEME . 'template.php';
require_once($link);
