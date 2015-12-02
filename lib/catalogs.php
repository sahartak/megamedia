<?php
define('CATALOG', 'CATALOG');

/**
 * Checks if a party has a catalog associated to it
 *
 * @param string $party_id the id of the party
 * @return boolean T party has catalog F it does not
 */
function catalog_party_has($party_id) {
	$probable_catalog_id = catalog_party_name($party_id);

	$query = "SELECT COUNT(*) as count
			  FROM prod_catalog
			  WHERE PROD_CATALOG_ID = '".esc($probable_catalog_id)."'";
	$data = db_query_to_row($query);

	return $data['count'] > 0;
}

/**
 * Gets the probable party catalog name if there is such
 *
 * @param string $party_id the id of the party
 * @return string the probable name of the catalog
 */
function catalog_party_name($party_id) {
	return sprintf('%s-%s', strtoupper($party_id), CATALOG);
}

/**
 * Gets catalog data
 *
 * @param string $catalog_id the id of the product catalog
 * @return array the catalog data or empty array
 */
function catalog_get($catalog_id) {
	$query = "SELECT *
			  FROM prod_catalog
			  WHERE PROD_CATALOG_ID = '".esc($catalog_id)."'";
	return db_query_to_row($query);
}

/**
 * Gets the categories that are part of a catalog
 *
 * @param string $catalog_id the id of the product catalog
 * @return array list with categories and their data
 */
function catalog_categories_get($catalog_id) {
	$query = "SELECT pcc.PROD_CATALOG_ID, pcc.PRODUCT_CATEGORY_ID, pc.DESCRIPTION, pc.CATEGORY_NAME
			  FROM prod_catalog_category pcc
			  JOIN product_category pc ON pcc.PRODUCT_CATEGORY_ID = pc.PRODUCT_CATEGORY_ID
			  WHERE pcc.PROD_CATALOG_ID ='".esc($catalog_id)."'
			  ORDER BY pcc.SEQUENCE_NUM ASC";
	return db_query_to_array($query);
}

/**
 * Adds a new category to a prod catalog
 *
 * @param string $prod_catalog_id the id of the product catalog
 * @param string $prod_category_id the id of the product category
 * @return void
 */
function catalog_categories_add($prod_catalog_id, $prod_category_id) {
	$query = "INSERT INTO prod_catalog_category (PROD_CATALOG_ID, PRODUCT_CATEGORY_ID, PROD_CATALOG_CATEGORY_TYPE_ID, FROM_DATE, CREATED_STAMP, CREATED_TX_STAMP)
			  VALUES ('".esc($prod_catalog_id)."', '".esc($prod_category_id)."', '', NOW(), '".now()."', NOW())";
	db_query($query);
}

/**
 * Adds a new product catalog to the database
 *
 * @param string $prod_catalog_id the id of the catalog
 * @param string $catalog_name the name of the catalog
 * @return void
 */
function catalog_add($prod_catalog_id, $catalog_name) {
	$query = "INSERT INTO prod_catalog (PROD_CATALOG_ID, CATALOG_NAME, CREATED_STAMP, CREATED_TX_STAMP)
			  VALUES ('".esc($prod_catalog_id)."', '".esc($catalog_name)."', '".now()."', NOW())";
	db_query($query);
}
