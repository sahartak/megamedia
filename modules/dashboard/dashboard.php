<?php
import('session');
import('catalogs');
import('forms');
import('orders');

$categories = array();

if (catalog_party_has($_SESSION['user']['PARTY_ID'])) {
	$catalog = catalog_get(catalog_party_name($_SESSION['user']['PARTY_ID']));
	$categories = catalog_categories_get($catalog['PROD_CATALOG_ID']);
}

$page = forms_get_page();
$orders = orders_get($page, ORDER_APPROVED, ORDER_NORMAL, $_SESSION['user']['PARTY_ID'], false, false, false);

$template = 'dashboard';
$link = THEME . 'template.php';
require_once($link);
