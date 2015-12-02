<?php
import('session');
import('products');
import('forms');
import('delivery');
import('html');

// Checking if all the fields are filled in
$empty = true;
// We do not check the motives anymore because we might
// have an empty line with no runs
//
// foreach ($_POST['MOTIVES'] as $_motive) {
// 	if ($_motive) {
// 		$empty = false;
// 		break;
// 	}
// }

foreach ($_POST['RUNS'] as $_run) {
	if ($_run) {
		$empty = false;
		break;
	}
}

// If the POST is emoty or there is no category then we resent
// the visitor back to the respective page because they came
// to this page following bad URL
if (empty($_POST) || $empty) {
	if (isset($_GET['PRODUCT_CATEGORY_ID']) && !empty($_GET['PRODUCT_CATEGORY_ID'])) {
		$url = sprintf('/orders/order?%s', http_build_query(array(
				'PRODUCT_CATEGORY_ID' => $_GET['PRODUCT_CATEGORY_ID'],
				'ERROR' => 'Please fill in the fields'
		)));
	} else {
		$url = '/dashboard/dashboard';
	}
	header('location: ' . $url);
	exit;
}

$product = products_get_by_type($_GET['PRODUCT_CATEGORY_ID'], PRODUCT_VISUAL);
$assocs = product_assoc_get($product['PRODUCT_ID'], PRODUCT_ASSOC_VARIANT);

foreach ($_POST['RUNS'] as $_index => $_run) {
	if ($_run && !$_POST['MOTIVES'][$_index]) {
		$errors[] = 'There are runs whithout motives attached to them';
		break;
	}
}

if (!empty($assocs)) {
	foreach ($_POST['MOTIVES'] as $_index => $_motive) {
		if ($_motive && $_POST['RUNS'][$_index]) {
			if (!isset($_POST['VARIANT'][$_index])) {
				$errors[] = 'Please select variant type';
				break;
			}
		}
	}
}

// If there are errors then we show the order form again
// with prefilled user data so the user can edit it
if (!empty($errors)) {
	$template = 'order';
	$link = THEME . 'template.php';
	require_once($link);
	exit;
}

// The data is good enough to go under validation
// 1. Checking if all motives have runs
// 2. Checking if all runs have motives
// 3. Checking if all motives have their variants selected
foreach ($_POST['MOTIVES'] as $_index => $_motive) {
	if ($_motive && !$_POST['RUNS'][$_index]) {
		unset($_POST['MOTIVES'][$_index]);
		unset($_POST['RUNS'][$_index]);
		unset($_POST['VARIANT'][$_index]);
		// $errors[] = 'All motives need to have an amount of runs filled in!';
		// break;
	}
}

list($headers, $rows, $variants, $copies, $delivery) = html_offer_box($_POST, [0]);

if (count($rows[1]) == 2) {
	foreach ($rows as $index => $_row) {
		unset($rows[$index]['MIXED']);
	}
}

// Storing to seesion the curent calculation
// and the current offer so we can add it to the cart
$_SESSION['CURRENT_CALCULATION'] = $_POST;
$_SESSION['CURRENT_OFFER']['PRODUCTS'] = $rows;
$_SESSION['CURRENT_OFFER']['DELIVERY'] = $delivery;

$template = 'calculator';
$link = THEME . 'template.php';
require_once($link);
