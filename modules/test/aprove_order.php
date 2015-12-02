<?php
if ((!$order_id = $_SESSION['order_id']) || !isset($_SESSION['user'])) {
	header('location: /');
	exit;
}
import('checkout');

$full_price = 0;

if ($campaign_orders = get_campaign_orders($order_id)) {
	$full_price += get_campaign_order_total_price($order_id, $campaign_orders);
}

if($banner_orders = get_additional_orders_by_type($order_id, 1)) {
	$full_price += get_additional_order_total_price($banner_orders, 1);
}

if($poster_orders = get_additional_orders_by_type($order_id, 2)) {
	$full_price += get_additional_order_total_price($poster_orders, 2);
}

if($rollup_orders = get_additional_orders_by_type($order_id, 3)) {
	$full_price += get_additional_order_total_price($rollup_orders, 3);
}

update_in_db('orders', array('total_price' => $full_price, 'status' => 1), "user_id = {$_SESSION['user']['id']} AND id = $order_id");
unset($_SESSION['order_id']);
header('location: /test/orders');
exit;



