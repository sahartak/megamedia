<?php
// Types of order roles
define('ORDER_CUSTOMER', 'CUSTOMER'); //The party who receives the goods
define('ORDER_PLACER'  , 'PLACER');     //The party who places the order
define('ORDER_RECEIVER', 'RECEIVER'); //The party who send the goods to the CUSTOMER

// Types of orders
define('ORDER_NORMAL', 'NORMAL'); //An ordinary product in an ordinary order

// Order statuses
define('ORDER_APPROVED', 'APPROVED');
define('ORDER_OFFER', 'OFFER');
define('ORDER_DELETED', 'DELETED');

/**
 *
 * Adds new order to the database
 * @param str $type				- type of the order (buy, sell)
 * @param str $external_id		- external id of the order
 * @param date $order_date		- the date of the order
 * @param str $created_by		- who created the order
 * @param str $currency			- currency of the prices
 * @param float $grand_total	- total with tax
 * @param str $company			- comapny placing the order
 * @param str $location			- location to deliver the order
 * @param date $delivery_date	- date of the delivery
 * @param str $dpd_tex			- dpd text
 * @param str $comments			- comments
 * @param float $grand_total_no_tax - the grand total of an order without the tax
 * @param string $cart			- the serialized cart from the session
 * @param boolean $is_offer		- show whether the saved is an offer or an order
 * @return int					- id of the order
 */
function order_place($type, $external_id, $order_date, $created_by, $currency, $grand_total, $company, $location, $delivery_date, $dpd_tex, $comments, $grand_total_no_tax, $cart, $is_offer){
	if ($is_offer) {
		$id = 'Offer_' . order_create_offer_id();
		$status = ORDER_OFFER;
	} else {
		$id = order_create_id();
		$status = ORDER_APPROVED;
	}

	$query = "INSERT INTO order_header (ORDER_ID, ORDER_TYPE_ID, EXTERNAL_ID, ORDER_DATE, ENTRY_DATE, STATUS_ID, CREATED_BY, CURRENCY_UOM, GRAND_TOTAL, CREATED_STAMP, CREATED_TX_STAMP,
										COMPANY, LOCATION, DELIVERY_DATE, DPD_TEXT, COMMENTS, GRAND_TOTAL_NO_TAX, SESSION_SERILIALIZE)
			  VALUES ('$id', '".esc($type)."', '".esc($external_id)."', '".esc($order_date)."', NOW(), '$status', '".esc($created_by)."', '".esc($currency)."', '".esc($grand_total)."',
			  		  '".now()."', NOW(), '".esc($company)."', '".esc($location)."', '".esc($delivery_date)."', '".esc($dpd_tex)."', '".esc($comments)."', '".esc($grand_total_no_tax)."', '". $cart ."')";
	db_query($query);

	return $id;
}

/**
 *
 * Changes the status of an order
 * @param str $order_id   - id of the order
 * @param str $new_status - new status for the order
 */
function order_status_change($order_id, $new_status){
	$query = "UPDATE order_header
			  SET STATUS_ID = '".esc($new_status)."', LAST_UPDATED_STAMP = '".now()."', LAST_UPDATED_TX_STAMP = NOW()
			  WHERE ORDER_ID='".esc($order_id)."'
			  LIMIT 1";
	db_query($query);
}

/**
 *
 * Creates sequential ids for the orders
 * @return int - new id of an order
 */
function order_create_id(){
	$query = "INSERT INTO id_order VALUES (NULL)";
	db_query($query);
	return mysql_insert_id();
}

/**
 *
 * Creates sequential ids for the offers
 * @return int - new id of an offer
 */
function order_create_offer_id() {
	$query = "INSERT INTO id_offer VALUES (NULL)";
	db_query($query);
	return mysql_insert_id();
}

/**
 *
 * Adds items to an order
 * @param int $order_id			- id of the order to which the item belongs
 * @param str $product_id		- product id of the item
 * @param str $product_category	- product category id
 * @param str $shopping_list_id	- shopping list id
 * @param int $quantity			- quantity ordered
 * @param float $price			- current single item price
 * @param str $item_description	- product name
 * @param str $change_by		- person who changes the order
 * @param float $tax			- tax percentage of the product
 * @param str $type				- type od the order item: NORMAL / FREE / CREDIT
 * @param $feature_quantity 	- the quantity of the runs ordered
 * @param $sequence				- the sequence of the ordered product
 * @param $feature_id 			- the if of the feature ordered / offer type
 * @param $list_price 			- the price of the main product
 * @param $recurring_price 		- the price of the runs products
 */
function order_item_add($order_id, $product_id, $product_category, $shopping_list_id, $quantity, $price, $item_description, $change_by, $tax, $currency, $type, $feature_quantity, $sequence, $feature_id,
						$list_price, $recurring_price){
	$query = "INSERT INTO order_item (ORDER_ID, PRODUCT_ID, PRODUCT_CATEGORY_ID, SHOPPING_LIST_ID, QUANTITY, UNIT_PRICE, ITEM_DESCRIPTION, CHANGE_BY_USER_LOGIN_ID, CREATED_STAMP,
									  CREATED_TX_STAMP, TAX_PERCENTAGE, CURRENCY_UOM_ID, ORDER_ITEM_TYPE_ID, FEATURE_QUANTITY, ORDER_ITEM_SEQ_ID, PRODUCT_FEATURE_ID,
									  UNIT_LIST_PRICE, UNIT_RECURRING_PRICE)
			  VALUES ('$order_id', '".esc($product_id)."', '".esc($product_category)."', '".esc($shopping_list_id)."', '".esc($quantity)."', '".esc($price)."', '".esc($item_description)."',
			  		  '".esc($change_by)."', '".now()."', NOW(), '".esc($tax)."', '".esc($currency)."', '".esc($type)."', '".esc($feature_quantity)."', '".esc($sequence)."', '".esc($feature_id)."',
			  		  '".esc($list_price)."', '".esc($recurring_price)."')";
	db_query($query);
}

/**
 *
 * Adds a role to an order
 * @param int $order_id     - id of the order
 * @param str $party_id		- id of the party / system user
 * @param str $role_type_id - id of the receiver
 */
function order_role_add($order_id, $party_id, $role_type_id){
	$query = "INSERT INTO order_role (ORDER_ID, PARTY_ID, ROLE_TYPE_ID, CREATED_STAMP, CREATED_TX_STAMP)
			  VALUES ('$order_id', '".esc($party_id)."', '$role_type_id', '".now()."', NOW())";
	db_query($query);
}

/**
 * Gets orders on a page
 *
 * @param int $page - number of the page
 * @param str $status_id - status of the orders
 * @param str $type      - type of the orders
 * @param str $search    - a search keyphrase
 * @param date $from_date - starting date of the orders
 * @param date $to_date   - ending date of the orders
 * @param bool $delivery_date - whether a delivery date(true) or order date (fasle)
 * @return arr - list with orders
 */
function orders_get($page, $status_id, $type, $search, $from_date, $to_date, $delivery_date){

	$where = order_search_build_where_clause($search, $from_date, $to_date, $delivery_date);

	if( is_array($status_id) ){
		$status_check = "AND oh.STATUS_ID IN ('".implode("','", $status_id)."')";
	}
	else{
		$status_check = "AND oh.STATUS_ID = '".esc($status_id)."'";
	}

	$query = "SELECT oh.ORDER_ID, oh.COMPANY, oh.CREATED_BY, oh.LOCATION, oh.EXTERNAL_ID, oh.ORDER_DATE, oh.DELIVERY_DATE, oh.STATUS_ID, oro.PARTY_ID, oh.GRAND_TOTAL, oh.DPD_TEXT,
					 oh.GRAND_TOTAL_NO_TAX, oh.CURRENCY_UOM, oh.SYNC_STATUS_ID, oh.COMMENTS, oh.SESSION_SERILIALIZE
			  FROM order_header oh JOIN order_role oro
			  ON oh.ORDER_ID = oro.ORDER_ID
			  WHERE oh.ORDER_TYPE_ID='".esc($type)."' $status_check AND oro.ROLE_TYPE_ID = '".ORDER_CUSTOMER."' $where
			  ORDER BY oh.ORDER_DATE DESC ";

	if( $page !== false ){
		$query .= db_get_limit($page);
	}
	return db_query_to_array($query);
}

/**
 *
 * Builds the search where clause for orders
 * @param str $search    - a search keyphrase
 * @param date $from_date - starting date of the orders
 * @param date $to_date   - ending date of the orders
 * @param bool $delivery_date - whether a delivery date(true) or order date (fasle)
 * @return str - the search clause
 */
function order_search_build_where_clause($search, $from_date, $to_date, $delivery_date){
	$where 	    = '';
	$search 	= esc($search);
	$from_date  = esc($from_date);
	$to_date    = esc($to_date);

	if( trim($search) != '' ){
		$where = " AND (oh.COMPANY LIKE '%$search%' OR oh.CREATED_BY LIKE '$search%' OR oh.LOCATION LIKE '$search%' OR oh.ORDER_ID LIKE '$search%' OR oro.PARTY_ID = '$search')";
	}

	if( $from_date != '' && $to_date != '' ){

		if($delivery_date){
			if( trim($from_date) != '' ){
				$where .= " AND DELIVERY_DATE >= '$from_date' ";
			}

			if( trim($to_date) != '' ){
				$where .= " AND DELIVERY_DATE <= '$to_date' ";
			}
		}
		else{
			if( trim($from_date) != '' ){
				$where .= " AND ORDER_DATE >= '$from_date' ";
			}

			if( trim($to_date) != '' ){
				$where .= " AND ORDER_DATE <= '$to_date' ";
			}
		}

	}

	return $where;
}

/**
 *
 * Gets the main infoamrtion for an order
 * @param str $order_id - id of the order
 * @return arr - info about the order
 */
function order_get($order_id){
	$query = "SELECT oh.ORDER_ID, oh.ORDER_TYPE_ID, oh.EXTERNAL_ID, oh.ORDER_DATE, oh.STATUS_ID, oh.CREATED_BY, oh.CURRENCY_UOM, oh.GRAND_TOTAL, oh.COMPANY, oh.LOCATION, oh.DELIVERY_DATE,
					 oh.DPD_TEXT, oh.COMMENTS, oro.PARTY_ID, oh.GRAND_TOTAL_NO_TAX, oh.SESSION_SERILIALIZE
			  FROM order_header oh JOIN order_role oro
			  ON oh.ORDER_ID = oro.ORDER_ID
			  WHERE oh.ORDER_ID = '".esc($order_id)."' AND oro.ROLE_TYPE_ID='".ORDER_CUSTOMER."'
			  LIMIT 1";
	return db_query_to_row($query);
}

/**
 *
 * Checks whether an order already exists
 * @param str $order_id - id of the order
 * @return boll - T/F
 */
function order_exists($order_id){
	$query = "SELECT COUNT(*)
			  FROM order_header
			  WHERE ORDER_ID = '".esc($order_id)."'";
	$data  = db_query_to_row($query);

	return $data['COUNT(*)'] > 0;
}

/**
 * Deletes an order and everything related to it
 * Deletes order items and roles too
 *
 * @param string $order_id the id of the order
 * @return void
 */
function order_delete($order_id) {
	$query = "DELETE FROM order_header WHERE ORDER_ID = '".esc($order_id)."'";
	db_query($query);

	$query = "DELETE FROM order_item WHERE ORDER_ID = '".esc($order_id)."'";
	db_query($query);

	$query = "DELETE FROM order_role WHERE ORDER_ID = '".esc($order_id)."'";
	db_query($query);
}

/**
 * Changes the id of an order, affects items and roles too
 *
 * @param string $order_id_from the current if of the order
 * @param string $order_id_to the new id of the order
 * @return void
 */
function order_change_id($order_id_from, $order_id_to) {
	$query = "UPDATE order_header
			  SET ORDER_ID = '".esc($order_id_to)."'
			  WHERE ORDER_ID = '".esc($order_id_from)."'";
	db_query($query);

	$query = "UPDATE order_item
			  SET ORDER_ID = '".esc($order_id_to)."'
			  WHERE ORDER_ID = '".esc($order_id_from)."'";
	db_query($query);

	$query = "UPDATE order_role
			  SET ORDER_ID = '".esc($order_id_to)."'
			  WHERE ORDER_ID = '".esc($order_id_from)."'";
	db_query($query);
}
