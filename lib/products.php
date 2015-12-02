<?php
//PRICE TYPES
define('CUSTOMER_PRICE', 'CUSTOMER_PRICE');
define('GENERAL_PRICE', 'GENERAL_PRICE');
define('SUPPLIER_PRICE', 'SUPPLIER_PRICE');

//PRODCUT TYPES
define('PRODUCT_VISUAL', 'VISUAL');
define('PRODUCT_VISUAL_VARIANT', 'VISUAL_VARIANT');
define('PRODUCT_RUN', 'RUN');
define('PRODUCT_RUN_VARIANT', 'RUN_VARIANT');

//PRODUCT ASSOC TYPES
define('PRODUCT_ASSOC_VARIANT', 'VARIANT');
define('PRODUCT_ASSOC_PAIR', 'PAIR');

//PRODUCT FEATURES PRICE RELATIONSHIPS
define('FEATURE_PRICE_ACCUMULATE', 'PRICE_ACCUMULATE');
define('FEATURE_PRICE_OVERRIDE', 'PRICE_OVERRIDE');

/*************************************
 *  PRODUCTS CATEGORIES FUNCTIONS
*************************************/

/**
 *
 * Gets data about a product category
 * @param str $category_id - id of the category
 * @return arr - row from the database regarding the category
 */
function product_category_get($category_id){
	$query = "SELECT PRODUCT_CATEGORY_ID, CATEGORY_NAME, DESCRIPTION
			  FROM product_category
			  WHERE PRODUCT_CATEGORY_ID = '".esc($category_id)."'
			  LIMIT 1";
	return db_query_to_row($query);
}

/**
 *
 * Gets all product categories
 * @param bool - is for client space
 * @return arr - list with categories
 */
function products_get_categories($client){
	$where = '';

	if( $client ){
		$where = 'WHERE SHOW_IN_SELECT = 1';
	}

	$query = "SELECT PRODUCT_CATEGORY_ID, CATEGORY_NAME, SHOW_IN_SELECT
			  FROM product_category
			  $where
			  ORDER BY CATEGORY_NAME ASC";
	return db_query_to_array($query);
}

/*************************************
 *  PRODUCTS FUNCTIONS
*************************************/

function products_search($keyword, $category, $role, $active, $visible, $frontend, $order = 'PRODUCT_ID'){
	$keyword = esc( trim($keyword) );
	$where   = '';
	$join    = '';

	if( trim($category) != '' ){
		$where .= " AND PRIMARY_PRODUCT_CATEGORY_ID = '".esc($category)."'";
	}

	if( trim($role) != '' ){
		$where .= " AND pr.ROLE_TYPE_ID = '".esc($role)."' ";
	}

	if( trim($active) != '' ){
		$join 	.= " JOIN product_attribute pa ON pa.PRODUCT_ID = p.PRODUCT_ID ";
		$where 	.= " AND pa.ATTR_NAME = 'ACTIVE' AND pa.ATTR_VALUE = '".esc($active)."' ";
	}else if( trim($visible) != '' ){
		$join 	.= " JOIN product_attribute pa ON pa.PRODUCT_ID = p.PRODUCT_ID ";
		$where 	.= " AND pa.ATTR_NAME = 'VISIBLE' AND pa.ATTR_VALUE = '".esc($visible)."' ";

		if($frontend){
			$where 	.= " AND pc.SHOW_IN_SELECT = '1' ";
		}
	}

	$query = "SELECT p.PRODUCT_ID, p.PRODUCT_NAME, pc.CATEGORY_NAME , rt.DESCRIPTION, pp.PRICE, pp.MINIMUM_ORDER, p.RETURNABLE, p.SMALL_IMAGE_URL,
			  p.LONG_DESCRIPTION, p.IS_VARIANT
			  FROM product p
			  JOIN product_category pc ON p.PRIMARY_PRODUCT_CATEGORY_ID = pc.PRODUCT_CATEGORY_ID
			  JOIN product_role pr ON p.PRODUCT_ID = pr.PRODUCT_ID
			  JOIN role_type rt ON pr.ROLE_TYPE_ID = rt.ROLE_TYPE_ID
			  JOIN product_price pp ON p.PRODUCT_ID=pp.PRODUCT_ID
			  $join
			  WHERE pp.PRODUCT_PRICE_TYPE_ID = '".GENERAL_PRICE."' AND pp.PARTY_ID = ''
				AND ( pc.CATEGORY_NAME LIKE '$keyword%' OR p.PRODUCT_ID LIKE '$keyword%' OR p.PRODUCT_NAME LIKE '$keyword%' OR rt.DESCRIPTION LIKE '$keyword%' )
				AND p.STATUS_ID != '".DELETED."' $where
			  ORDER BY p.$order ASC";
	return db_query_to_array($query);
}

/**
 * Gets products by type and category
 * ATTENTION: In this version of the CRM/ERP
 * there is one main product per category for the calculator
 *
 * @param string $category the category in which the product is
 * @param string $type the type of the product
 * @return array with data about the product
 */
function products_get_by_type($category, $type) {
	$query = "SELECT *
			  FROM product
			  WHERE PRIMARY_PRODUCT_CATEGORY_ID = '".esc($category)."' AND PRODUCT_TYPE_ID = '".esc($type)."'
			  LIMIT 1";

	return db_query_to_row($query);
}

/**
 *
 * Gets the stock relation of a product
 * @param str $product_id - id of the initial product
 * @param str $type - type of the association
 * @param bool $single - whether to get a single row
 * @return arr - info about the relationship
 */
function product_assoc_get($product_id, $type, $single = false){
	$query = "SELECT pa.PRODUCT_ID, p.PRODUCT_NAME
			  FROM product_assoc pa
			  JOIN product p ON p.PRODUCT_ID = pa.PRODUCT_ID
			  WHERE pa.PRODUCT_ID_TO = '".esc($product_id)."' AND pa.PRODUCT_ASSOC_TYPE_ID = '".esc($type)."'";

	if ($single = false) {
		return db_query_to_row($single);
	}

	return db_query_to_array($query);
}

/**
 * Gets all possible prices for all possible
 * variations/features of the product
 *
 * @param string $product the id of the product
 * @param string $party_id the id of the client of such exists
 * @param bookean $apply whether the function to apply the price
 * relationship between the product and the feature
 * @return array with data
 */
function product_get_all_prices($product, $party_id, $apply = true)  {
	// Getting general price for product
	if ($party_id) {
		$product_price = product_price_get($product, CUSTOMER_PRICE, $party_id);
	}

	if (empty($product_price)) {
		$product_price = product_price_get($product, GENERAL_PRICE, '');
	}

	// Adding features and their prices if such exist
	$features = product_get_features($product);

	// If there are fetures we get their prices too
	if (!empty($features)) {
		foreach ($features as &$_feature) {
			$feature_price = product_feature_price_get($_feature['PRODUCT_FEATURE_APPL_ID'], CUSTOMER_PRICE, $party_id);

			if (empty($feature_price)) {
				$feature_price = product_feature_price_get($_feature['PRODUCT_FEATURE_APPL_ID'], GENERAL_PRICE, '');
			}

			// If we apply the price relationships then we calculate if the prices accumulate
			// In the other case when the price overrides there is nothing to do
			// if ($_feature['PRODUCT_FEATURE_APPL_ID'] == 'ADSHEL-4/3-COPY-OFFS') {
			// 	echo '<pre>';
			// 	var_dump($_feature);
			// 	var_dump($feature_price);
			// 	echo '</pre>';
			// }

			if ($apply) {
				if ($_feature['PRODUCT_FEATURE_APPL_TYPE_ID'] == FEATURE_PRICE_ACCUMULATE) {
					if (isset($product_price['PRICE'])) {
						$feature_price['PRICE'] += $product_price['PRICE'];
					}
				}
			}
			$_feature = array_merge($_feature, $feature_price);
		}

		$product_price['FEATURES'] = $features;
	}

	return $product_price;
}

/**
 * Gets a price for a product
 *
 * @param string $product_id the id of the product
 * @param string $price_type the type of the pricing
 * @param string $party_id the customer id
 * @return void
 */
function product_price_get($product_id, $price_type, $party_id) {
	$where = '';
	if ($party_id != '') {
		$where = " AND PARTY_ID = '".esc($party_id)."'";
	}

	$query = "SELECT PRICE
			  FROM product_price
			  WHERE PRODUCT_ID = '".esc($product_id)."' AND PRODUCT_PRICE_TYPE_ID = '".esc($price_type)."' $where
			  LIMIT 1";

	return db_query_to_row($query);
}

/*************************************
 *  PRODUCTS FEATURES FUNCTIONS
*************************************/
/**
 * Gets all features for a product
 *
 * @param string $product_id the id of the product
 * @return array list with features
 */
function product_get_features ($product_id) {
	$query = "SELECT PRODUCT_FEATURE_APPL_ID, PRODUCT_FEATURE_ID, PRODUCT_FEATURE_APPL_TYPE_ID, PRODUCTION_DAYS, DELIVERY_DAYS
			  FROM product_feature_appl
			  WHERE PRODUCT_ID = '".esc($product_id)."'";
	return db_query_to_array($query);
}

/**
 * Gets the price of a feature of a product
 *
 * @param string $feature_appl_id the id of the applied feature
 * @param string $price_type the type of the price
 * @param string $party_id the id of the customer
 * @return array with price
 */
function product_feature_price_get($feature_appl_id, $price_type, $party_id) {
	$where = '';
	if ($party_id != '') {
		$where = " AND PARTY_ID = '".esc($party_id)."'";
	}

	$query = "SELECT PRICE
			  FROM product_feature_appl_price
			  WHERE PRODUCT_FEATURE_APPL_ID = '".esc($feature_appl_id)."' AND PRODUCT_PRICE_TYPE_ID = '".esc($price_type)."' $where
			  LIMIT 1";

	return db_query_to_row($query);
}

/**
 * Gets all possible product features
 *
 * @param boolean $list if we want a list with PRODUCT_FEATURE_ID-s only
 * @return array list with product features
 */
function product_feature_all($list = false) {
	$query = "SELECT PRODUCT_FEATURE_ID, PRODUCT_FEATURE_TYPE_ID, DESCRIPTION
			  FROM product_feature
			  ORDER BY PRODUCT_FEATURE_ID ASC";

	if ($list) {
		return db_query_to_list($query, 'PRODUCT_FEATURE_ID');
	}

	return db_query_to_array($query);
}

/**
 * Deletes the price of a feature of a product
 *
 * @param string $feature_appl_id the id of the applied feature
 * @param string $price_type the type of the price
 * @param string $party_id the id of the customer
 * @return void
 */
function product_feature_price_delete($feature_appl_id, $price_type, $party_id) {
	$query = "DELETE FROM product_feature_appl_price
			  WHERE PRODUCT_FEATURE_APPL_ID = '".esc($feature_appl_id)."' AND
			  		PRODUCT_PRICE_TYPE_ID = '".esc($price_type)."' AND
			  		PARTY_ID = '".esc($party_id)."'
			  LIMIT 1";
	db_query($query);
}

/**
 * Checks if the price of a feature of a product exists
 *
 * @param string $feature_appl_id the id of the applied feature
 * @param string $price_type the type of the price
 * @param string $party_id the id of the customer
 * @return boolean true there is such price false there is no such price
 */
function product_feature_price_exists($feature_appl_id, $price_type, $party_id) {
	$query = "SELECT COUNT(*) as cnt
			  FROM product_feature_appl_price
			  WHERE PRODUCT_FEATURE_APPL_ID = '".esc($feature_appl_id)."' AND
			  		PRODUCT_PRICE_TYPE_ID = '".esc($price_type)."' AND
			  		PARTY_ID = '".esc($party_id)."'";
	$data = db_query_to_row($query);

	return $data['cnt'] > 0;
}

/**
 * Saves the price of a feature
 *
 * @param string $feature_appl_id the id of the applied feature
 * @param string $price_type the type of the price
 * @param string $party_id the id of the customer
 * @param float $price the new price of the feature
 * @param string $currency the currency of the product
 * @return boolean true there is such price false there is no such price
 */
function product_feature_price_add($feature_appl_id, $price_type, $party_id, $price, $currency) {
	$query = "INSERT INTO product_feature_appl_price (PRODUCT_FEATURE_APPL_ID, PRODUCT_PRICE_TYPE_ID, CURRENCY_UOM_ID, FROM_DATE, PRICE, CREATED_DATE, CREATED_BY_USER_LOGIN, CREATED_STAMP, CREATED_TX_STAMP, PARTY_ID)
			  VALUES ('".esc($feature_appl_id)."', '".esc($price_type)."', '".esc($currency)."', NOW(), '".esc($price)."', NOW(), '".esc($_SESSION['user']['PARTY_ID'])."', '".now()."', NOW(), '".esc($party_id)."')";
	db_query($query);
}

/*************************************
 *  CALCULATOR FUNCTIONS
 ************************************/

/**
 * Calculates the price by the header of the price
 * offer box which in fact is the product_feature
 *
 * @param string $product_id the id of the product
 * @param array $prices with specified format so the prices can be extracted
 * @param string $header the header of the box
 * @return float the price for the product
 */
function product_price_by_header($product_id, $prices, $header) {
	foreach ($prices as $_feature) {
		if ($header == $_feature['PRODUCT_FEATURE_ID']) {
			return $_feature['PRICE'];
		}
	}
}

function product_assoc_get_price($unit_list_price, $feature, $party_id) {
	$query = "SELECT PRODUCT_ID
			  FROM product_feature_appl
			  WHERE PRODUCT_FEATURE_APPL_ID = '".$unit_list_price."'
			  LIMIT 1";
	$data = db_query_to_row($query);
	$run_id = $data['PRODUCT_ID'] . '-COPY';

	$query = "SELECT PRODUCT_FEATURE_APPL_ID
			  FROM product_feature_appl
			  WHERE PRODUCT_ID = '$run_id' AND PRODUCT_FEATURE_ID = '$feature'
			  LIMIT 1";
	$data = db_query_to_row($query);

	$feature_price = product_feature_price_get($data['PRODUCT_FEATURE_APPL_ID'], CUSTOMER_PRICE, $party_id);
	if (empty($feature_price)) {
		$feature_price = product_feature_price_get($data['PRODUCT_FEATURE_APPL_ID'], GENERAL_PRICE, '');
	}

	return $feature_price['PRICE'];
}
