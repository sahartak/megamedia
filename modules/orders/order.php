<?php
import('session');
import('products');
import('forms');

$category = product_category_get($_GET['PRODUCT_CATEGORY_ID']);

if (!empty($category)) {
	$product = products_get_by_type($_GET['PRODUCT_CATEGORY_ID'], PRODUCT_VISUAL);
	$assocs  = product_assoc_get($product['PRODUCT_ID'], PRODUCT_ASSOC_VARIANT);
} else {
	$errors[] = sprtinf('Category: "%s" does not exist');
}

// If there was an error we display it
if (isset($_GET['ERROR'])) {
	$errors[] = $_GET['ERROR'];
}

if (isset($_GET['success'])) {
	$success[] = $_GET['success'];
}

// If the clients wants to do a recalculation then we
// set the post to the current calculation and the the
// view will take care of the rest
if (isset($_GET['recalculate'])) {
	if (!empty($_SESSION['CURRENT_CALCULATION'])) {
		$_POST = $_SESSION['CURRENT_CALCULATION'];
	}
}

$template = 'order';
$link = THEME . 'template.php';
require_once($link);
