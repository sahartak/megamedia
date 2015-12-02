<?php
import('session');
import('forms');
import('person');
import('products');

if (!is_admin()) {
	header('location: /users/logut');
	exit;
}

$party_id = forms_get('PARTY_ID');
define('PAGE_PRICE_TYPE', $party_id ? CUSTOMER_PRICE : GENERAL_PRICE);

if (isset($_POST) && !empty($_POST)) {
	// Saving prices for a customer
	foreach ($_POST['APPL_TYPE_ID'] as $product_feat_appl_id => $price) {
		// If the price is empty we delete it
		$price = str_replace(',', '.', $price);

		if ((int)$price == 0 || product_feature_price_exists($product_feat_appl_id, PAGE_PRICE_TYPE, $party_id)) {
			product_feature_price_delete($product_feat_appl_id, PAGE_PRICE_TYPE, $party_id);
		}

		if ((int)$price > 0) {
			product_feature_price_add($product_feat_appl_id, PAGE_PRICE_TYPE, $party_id, $price, DEFAULT_CURRENCY);
		}
	}
}

if ($party_id) {
	$person = person_get($party_id);
} else {
	$person['FIRST_NAME'] = 'General';
	$person['LAST_NAME'] = 'Prices';
}

$categories = products_get_categories(false);

$template = set_template('products', 'prices');
$link = THEME . 'template.php';
require_once($link);
