<?php
if(!$order_id = $_SESSION['order_id'])
	die('no order');

$campaign_id = abs((int)$_POST['campaign']);
$result = db_query("SELECT 1 FROM campaign_orders WHERE id = $campaign_id AND order_id = $order_id");
if(mysql_num_rows($result)) {
	$amount = abs((int)$_POST['amount']);
	$id = abs((int)$_POST['id']);
	$type = abs((int)$_POST['type']);
	if(in_array($type, array(1,2,3,4))) {
		if($id && $campaign_id && $type) {
			db_query("UPDATE campaign_orders_items SET type_$type = '$amount' WHERE id = $id AND parent_id = $campaign_id");
		}
	}
}