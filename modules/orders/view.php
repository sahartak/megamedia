<?php
import('session');
import('products');
import('delivery');
import('html');
import('contacts');
import('orders');
import('shipments');
import('forms');
import('contacts');
import('documents');

$order_id = $_GET['id'];
if (order_exists($order_id)) {
	$order = order_get($order_id);
	if ($order['PARTY_ID'] == $_SESSION['user']['PARTY_ID']) {
		$order_cart = unserialize($order['SESSION_SERILIALIZE']);
		$documents = documents_get($order_id);
		$shipment = shipment_get($order_id);

		if (!empty($shipment)) {
			if ($shipment['DESTINATION_CONTACT_MECH_ID'] == SHIPMENT_REQUISITION) {
				$address = 'Look at requisition files';
			} else {
				$address = contact_generate_string(contact_mech_get_attributes($shipment['DESTINATION_CONTACT_MECH_ID']));
			}
		}

	} else {
		$errors[] = sprintf('Order %d does not belong to you!', $order_id);
	}
} else {
	$errors[] = sprintf('Order %d does not exist!', $order_id);
}

$template = set_template('orders', 'view');
$link = THEME . 'template.php';
require_once($link);
