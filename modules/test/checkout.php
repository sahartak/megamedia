<?php
if((!$order_id = $_SESSION['order_id']) || !isset($_SESSION['user'])) {
	header('location: /');
	exit;
}

import('checkout');
import('store');

$materials = get_materials();

$campaign_orders = get_campaign_orders($order_id);
$order_weeks = get_order_weeks($order_id);
$banner_orders = get_additional_orders_by_type($order_id, 1);
$poster_orders = get_additional_orders_by_type($order_id, 2);
$rollup_orders = get_additional_orders_by_type($order_id, 3);

$campaign_stores = get_campaign_stores($order_id);
$campaign_totals = get_campaign_items_total($order_id);
$campaign_orders_list = get_campaign_orders_list($order_id);
$prices = get_campaign_prices();

$add_prices = get_additional_prices();

$template_name = '';
if($campaign_orders && !$banner_orders && !$poster_orders && !$rollup_orders) {
	$template_name = 'campaign_checkout';
} elseif(!$campaign_orders && $banner_orders && !$poster_orders && !$rollup_orders) {
	$template_name = 'banners_checkout';
} elseif(!$campaign_orders && !$banner_orders && $poster_orders && !$rollup_orders) {
	$template_name = 'posters_checkout';
} elseif(!$campaign_orders && !$banner_orders && !$poster_orders && $rollup_orders) {
	$template_name = 'rollups_checkout';
} elseif($campaign_orders || $banner_orders || $poster_orders || $rollup_orders) {
	$template_name = 'full_checkout';
}

if(!$template_name) {
	header('location: /');
	exit;
}

$stores = db_query_to_array("SELECT * FROM stores");
if($banner_orders) {
	$stores_methods = array();
	foreach($stores as $store) {
		$stores_methods[$store['id']] = get_store_hanging_methods($store['id']);
	}
}

$template = set_template('test', $template_name);
$link = THEME . 'template2.php';

require_once($link);