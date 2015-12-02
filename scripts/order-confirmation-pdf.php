<?php

import('person');
import('orders');
import('contacts');
import('shipments');
import('products');
import('html');
import('delivery');

/**
 * Builds the actual offer box with all the
 * data populated in it
 *
 * @param array $rows with data about the box
 * @param array $variants the variants of the products
 * @param array $copies the copies variants of the products
 * @param array $delivery the delivery price
 * @param array $_header to be the label like DIGITAL / OFFSET
 * @param array $motives the motives of the product
 * @param array $display whether to hide or display some data
 * @param boolean $allow_actions whether we allow actions to happen
 * @return string the HTML code to build the box
 */
function pdf_build_offer_box($rows, $variants, $copies, $delivery, $_header, $motives, $display, $allow_actions, $runs, $selected_variants, $client) {
static $box_idnex = 0;

	// We need to check if there is any need to display that row at all
	$check = 0;
	if (!empty($motives)) {
		foreach ($motives as $index => $_motive) {
			if (!$_motive) {
				continue;
			}

			if (!isset($rows[$index][$_header])) {
				continue;
			}

			$check += $rows[$index][$_header];
		}
	}

	if (!$check) {
		return '';
	}

	$return = '';
	$total = 0;
	foreach ($motives as $index => $_motive) {
		// If we do not have a motive for that line then
		// we simply do not display it
		if (!$_motive) {
			continue;
		}
		$total += $rows[$index][$_header];

		$product_padding = '4px';
		$return .= '<tr>
						<td style=" padding: '.$product_padding.';">'.substr($variants[1], 0, stripos($variants[1], '-')) . ' <small>(' . $_header . ')</small></td>
						<td style=" padding: '.$product_padding.';">' . $index . '</td>
						<td style=" padding: '.$product_padding.';">' . $motives[$index] . '</td>
						<td style=" padding: '.$product_padding.';">' . $runs[$index] . '</td>
						<td style=" padding: '.$product_padding.';">' . substr($selected_variants[$index], stripos($selected_variants[$index], '-') + 1) .'</td>';

						if ($client) {
							$return .= '<td style=" padding: '.$product_padding.'; text-align: right">
								<strong style="margin-left:25px;">' . number_format($rows[$index][$_header], 2) . '</strong>
								' . DEFAULT_CURRENCY . '
							</td>';
						}
					$return .= '</tr>';

				}

		$return .= '
				<!-- <tr>
					<td style=" padding: '.$product_padding.';background-color: #fff;" class="span1">Environment </td>
					<td style=" padding: '.$product_padding.';"></td>
					<td style=" padding: '.$product_padding.';"></td>
					<td style=" padding: '.$product_padding.';"></td>
					<td style=" padding: '.$product_padding.';"></td>';

						if ($client) {
							$return .= '<td style=" padding: '.$product_padding.';background-color: #fff; text-align: right;" class="span2">
								<strong style="margin-left:25px;">' . number_format(DELIVERY_ENVIRONMENT, 2) . '</strong>
								' . DEFAULT_CURRENCY . '
							</td>';
						}
				$return .= '</tr>
				<tr>
					<td style=" padding: '.$product_padding.';background-color: #fff;" class="span1">Freight </td>
					<td style=" padding: '.$product_padding.';"></td>
					<td style=" padding: '.$product_padding.';"></td>
					<td style=" padding: '.$product_padding.';"></td>
					<td style=" padding: '.$product_padding.';"></td>';

						if ($client) {
							$return .= '<td style=" padding: '.$product_padding.';background-color: #fff; text-align: right;" class="span2">
								<strong style="margin-left:25px;">' . number_format($delivery, 2) . '</strong>
								' . DEFAULT_CURRENCY . '
							</td>';
						}
				$return .= '</tr> -->
				<tr>
					<td style=" padding: '.$product_padding.';background-color: #fff;" class="span1">Subtotal </td>
					<td style=" padding: '.$product_padding.';"></td>
					<td style=" padding: '.$product_padding.';"></td>
					<td style=" padding: '.$product_padding.';"></td>
					<td style=" padding: '.$product_padding.';"></td>';

						if ($client) {
							$return .= '<td class="span3" style=" padding: '.$product_padding.';text-align: right;">
								<strong style="margin-left:25px;">' . number_format($total, 2) . '</strong>
								' . DEFAULT_CURRENCY . '
							</td>';
						}
				$return .= '</tr>';

	$box_idnex++;

	return [$return, $total, $delivery];
}

function build_customer_pdf($order_id, $person_id, $client = true) {
	$system_settings = get_settings();
	$order = order_get($order_id);
	$person = person_get($person_id);
	$contacts = contact_get_by_party($person_id, COMPANY_CONTACT);
	$affiliate = person_affiliate_get($person_id);

	// Dealing with shipments
	$shipment = shipment_get($order_id);
	if ($shipment['DESTINATION_CONTACT_MECH_ID'] == SHIPMENT_REQUISITION) {
		$delivery_address = 'In requisions files';
	} else {
		$attributes = contact_mech_get_attributes($shipment['DESTINATION_CONTACT_MECH_ID']);
		$delivery_address = contact_generate_string($attributes, '<br />');
	}

	$content = '
	<page backtop="30mm" backbottom="14mm" backleft="10mm" backright="10mm" style="font-size: 12px; font-family: Arial; margin-top: 150px;">

		<!-- Page header -->
		<page_header style="margin-left: 20px; height: 400px; margin-bottom: 40px;">
			<table style="margin-left: 15px;">
				<tr>
					<td style="width: 530px;">
						<div style="background-color: #63cdf5; padding-left: 10px;">
							<h1>Order #'.$order_id.'</h1>
						</div>
					</td>
					<td>';

					if ($client) {
						$content .= '<img style="width: 200px;" src="./themes/megamedia/img/website-logo-big.png">';
					}

		$content .= '</td></tr>
			</table>
			<div style="clear: both"></div>
		</page_header>


		<!-- Metadata -->
		<table style="margin-left: -30px;" cellspacing="10" cellspadding="0">
			<tr>
				<td style="width: 390px; vertical-align:top; border: 1px solid #fcb040; border-top: none; padding:0; padding-bottom: 10px;">
					<div style="background-color: #fcb040; width: 390px; padding-left: 10px; margin-bottom: 10px;">
						<h3 style="margin:10 0 10 0; padding: 0;">Customer</h3>
					</div>
					<div style="font-size: 15px; margin-left: 10px; width: 390px;">
						'.contact_generate_string($contacts, '<br />').'
					</div>
				</td>';

				if ($client) {
					$content .= '<td style="width: 300px; vertical-align:top; border: 1px solid #fcb040; border-top: none; padding:0; padding-bottom: 10px;">
						<div style="background-color: #fcb040; width: 300px; padding-left: 10px; margin-bottom: 10px;">
							<h3 style="margin:10 10 10 10; padding: 0;">Megamedia DK ApS</h3>
						</div>
						<div style="font-size: 15px; margin-left: 10px; width: 300px;">
							Skøjtevej 19A <br />
							2770 Kastrup <br />
							Denmark <br />
							Tlf.: +45 70 26 26 99 <br />
							<a href="www.megamedia.dk">www.megamedia.dk</a> <br />
							Account manager: <b>'.$affiliate['AFFILIATE_NAME'].'</b> <br />
							Telephone: <b>'.$affiliate['AFFILIATE_DESCRIPTION'].'</b>
						</div>
					</td>';
				}

			$content .= '</tr>
			<tr>
				<td style="width: 390px; vertical-align:top; border: 1px solid #fcb040; border-top: none; padding:0; padding-bottom: 10px;">
					<div style="background-color: #fcb040; width: 390px; padding-left: 10px; margin-bottom: 10px;">
						<h3 style="margin:10 0 10 0; padding: 0;">Delivery Address</h3>
					</div>
					<div style="font-size: 15px; margin-left: 10px; width: 390px;">
						'.$delivery_address.'
					</div>
				</td>';

				if ($client) {
					$content .= '<td style="width: 300px; vertical-align:top; border: 1px solid #fcb040; border-top: none; padding:0; padding-bottom: 10px;">
						<div style="background-color: #fcb040; width: 300px; padding-left: 10px; margin-bottom: 10px;">
							<h3 style="margin:10 10 10 10; padding: 0;">Order Details</h3>
						</div>
						<div style="font-size: 15px; margin-left: 10px; width: 300px;">
							Order done by: <b>'.$person['FIRST_NAME'].' '.$person['LAST_NAME'].'</b><br />
							Order date: <b>'.date_convert($order['ORDER_DATE'], DEFAULT_DATE_FORMAT).'</b><br />
							Your reference: <b>'.$order['EXTERNAL_ID'].'</b><br />
							Additional order info: <b>'.$order['COMMENTS'].'</b>
						</div>
					</td>';
				}

			$content .= '</tr>
		</table>


		<!-- Product section -->
		<div style="margin-left: -20px; width: 715px; background-color: #CB68A8; padding-left: 10px; margin-top: 20px;">
			<h3 style="margin:10 10 10 10; padding: 0;">Products</h3>
		</div>

		<table style="font-size: 15px; margin-left: -20px; width: 715px; margin-top: 10px;"border="1px" cellspacing="0" >
			<tr>
				<th style="width: 228px; padding: 5px;"><b>Product</b></th>
				<th style="width: 35px; padding: 5px;"><b>Line</b></th>
				<th style="width: 55px; padding: 5px;"><b>Motives</b></th>
				<th style="width: 55px; padding: 5px;"><b>Runs</b></th>
				<th style="width: 85px; padding: 5px;"><b>Variant</b></th>';

				if ($client) {
					$content .= '<th style="width: 95px; padding: 5px;"><b>Price</b></th>';
				}
			$content .= '</tr>';

		$Cart = new Cart;
		$Cart->fromString($order['SESSION_SERILIALIZE']);
		$product_count = $Cart->getProductsCount();
		$loop = 0;
		$total = 0;
		$total_delivery = 0;
		foreach ($Cart->getProducts() as $i => $_row) {
			$loop++;
			list($headers, $rows, $variants, $copies, $delivery) = html_offer_box($_row['PRODUCTS'], $Cart->deliveryDistributionAt($i));
			list($html, $add_to_total, $delivery) = pdf_build_offer_box($rows, $variants, $copies, $delivery, $_row['OFFER'], $_row['PRODUCTS']['MOTIVES'], false, true, $_row['PRODUCTS']['RUNS'], $_row['PRODUCTS']['VARIANT'], $client);
			$content .= $html;
			$total += $add_to_total;
			$total_delivery += $delivery;
			if ($product_count > $loop) {
				$content .='<tr><td style="border-left: none; border-right: none;" colspan="6">&nbsp;</td></tr>';
			}
		}


		$content .= '</table>

		<!-- Grand Total -->
		<div style="clear: both; margin-top: 40px;">
			<div style="text-align: right; margin-left: -20px; width: 723px; background-color: #CB68A8; padding-right: 10px; margin-top: 20px;">
				<span style="margin:10 10 10 10; padding: 0;">All Delivery + Environment:
				<span style="color: #fff;">'.number_format($total_delivery + DELIVERY_ENVIRONMENT + $system_settings['ORDER_FEE'], 2).' '.DEFAULT_CURRENCY.'</span></span>
			</div>
		</div>
		<div style="clear: both; margin-top: 40px;">
			<div style="text-align: right; margin-left: -20px; width: 723px; background-color: #CB68A8; padding-right: 10px; margin-top: 20px;">
				<h3 style="margin:10 10 10 10; padding: 0;">Grand Total:
				<span style="color: #fff;">'.number_format($total + $total_delivery + DELIVERY_ENVIRONMENT + $system_settings['ORDER_FEE'], 2).' '.DEFAULT_CURRENCY.'</span></h3>
			</div>
		</div>


		<!-- Footer -->
		<page_footer>
			<table style="width: 750px;">
				<tr>
					<td class="center">Order: #'.$order_id.' <i style=" font-size: 10px;">page [[page_cu]]/[[page_nb]]</i></td>
				</tr>
			</table>
		</page_footer>
	</page>';

	try
	{
		$html2pdf = new HTML2PDF('P', 'A4', 'en');
		$html2pdf->setDefaultFont('Arial');
		$html2pdf->writeHTML($content, false);

		$file_name = $client ? 'Customer-Copy-' : 'Production-Copy-';
		$prefix = $file_name .  $order_id;
		$pdf_name = $prefix.'.pdf';

		$html2pdf->Output(PDFS_PATH . $pdf_name, 'F');
	}
	catch(HTML2PDF_exception $e) {
		echo $e;
		exit;
	}

	return PDFS_PATH . $pdf_name;
}
