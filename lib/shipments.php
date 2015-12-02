<?php

define('SHIPMENT_TO_CLIENT', 'TO_CLIENT');
define('SHIPMENT_REQUISITION', 'REQUISITION');

/**
 *
 * Creates new shippment
 * @param str $type				- creates new shipment
 * @param int $order_id			- order connected to the shipment
 * @param str $currency			- currency of the shipment
 * @param str $origin_address	- origin adress from where the goods start
 * @param str $origin_phone	    - phone contact of goods sender
 * @param str $dest_address		- address where the goods arrive
 * @param str $dest_phone		- phone contact of goods receiver
 * @param str $party_id_to		- party sending the goods
 * @param str $party_id_from	- party receiveing the goods
 * @param str $created_by		- admin who created the shipment
 * @param str $handling			- additional handling informatio
 *
 * @return str - new shipment id
 */
function shipment_add($type, $order_id, $currency, $origin_address, $origin_phone, $dest_address, $dest_phone, $party_id_to, $party_id_from, $created_by, $handling){
	$shipment_id = 'SH-'.$order_id;

	$query = "INSERT INTO shipment (SHIPMENT_ID, SHIPMENT_TYPE_ID, PRIMARY_ORDER_ID, CURRENCY_UOM_ID, ORIGIN_CONTACT_MECH_ID, ORIGIN_TELECOM_NUMBER_ID, DESTINATION_CONTACT_MECH_ID,
									DESTINATION_TELECOM_NUMBER_ID, PARTY_ID_TO, PARTY_ID_FROM, CREATED_DATE, CREATED_BY_USER_LOGIN, CREATED_STAMP, CREATED_TX_STAMP, HANDLING_INSTRUCTIONS)
			  VALUES ('$shipment_id', '".esc($type)."', '".esc($order_id)."', '".esc($currency)."', '".esc($origin_address)."', '".esc($origin_phone)."', '".esc($dest_address)."',
			  		  '".esc($dest_phone)."', '".esc($party_id_to)."', '".esc($party_id_from)."', NOW(), '".esc($created_by)."', '".now()."', NOW(), '".esc($handling)."')";
	db_query($query);

	return $shipment_id;
}

/**
 *
 * Gets a shipment by the id of the order related to id
 * @param int $order_id - id of the order
 * @return arr - information about the shipment
 */
function shipment_get($order_id){
	$query = "SELECT SHIPMENT_ID, SHIPMENT_TYPE_ID, PRIMARY_ORDER_ID, CURRENCY_UOM_ID, ORIGIN_CONTACT_MECH_ID, ORIGIN_TELECOM_NUMBER_ID, DESTINATION_CONTACT_MECH_ID, DESTINATION_TELECOM_NUMBER_ID, PARTY_ID_TO,
				     PARTY_ID_FROM, CREATED_DATE, CREATED_BY_USER_LOGIN, LAST_MODIFIED_DATE, LAST_MODIFIED_BY_USER_LOGIN, HANDLING_INSTRUCTIONS
			  FROM shipment
			  WHERE PRIMARY_ORDER_ID = '".esc($order_id)."'
			  LIMIT 1";
	return db_query_to_row($query);
}

/**
 * Returns the shipment costs for a certain amount of items
 *
 * @param integer $units the number of items ordered
 * @return array list with data about the deliveries
 */
function shipment_cost_estimate($units) {
	// There is no price for no items
	if (!$units) {
		return 0.0;
	}

	$query = "SELECT sce.SHIPMENT_METHOD_TYPE_ID, sce.QUANTITY_BREAK_ID, sce.QUANTITY_UNIT_PRICE, sce.PRICE_UOM_ID, qb.FROM_QUANTITY, qb.THRU_QUANTITY
			  FROM shipment_cost_estimate sce JOIN quantity_break qb
			  ON sce.QUANTITY_BREAK_ID = qb.QUANTITY_BREAK_ID
			  WHERE qb.FROM_QUANTITY <= ".esc($units)." AND qb.THRU_QUANTITY >= ".esc($units);
	$data = db_query_to_row($query);

	// It was so much quantity that it could not fit on a single delivery method
	if (empty($data)) {
		$max = shipment_get_max_break();
		$max_times = floor($units / $max);
		$left = $units % $max;

		$max_deliveries = shipment_cost_estimate($max);
		$left_delivery = shipment_cost_estimate($left);

		return ((float)$max_deliveries * $max_times) + (float)$left_delivery;
	}

	// We could find a single delivery method for this shipment
	return (float)$data['QUANTITY_UNIT_PRICE'];
}

function shipment_get_max_break() {
	$query = "SELECT MAX(THRU_QUANTITY) as max_units FROM quantity_break";
	$data = db_query_to_row($query);

	return $data['max_units'];
}
