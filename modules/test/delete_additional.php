<?php
if((!$order_id = $_SESSION['order_id']) || !isset($_POST['id']))
	die('no order');

$id = abs((int)$_POST['id']);

if($id) {
    $result = delete_from_db('additional_orders_items', "id = $id AND order_id = $order_id");
}