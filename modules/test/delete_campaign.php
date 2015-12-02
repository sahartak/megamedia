<?php
if(!$order_id = $_SESSION['order_id'])
	die('no order');

$campaign_id = abs((int)$_POST['campaign']);
$result = db_query("SELECT 1 FROM campaign_orders WHERE id = $campaign_id AND order_id = $order_id");
if(mysql_num_rows($result)) {
	db_query("DELETE FROM campaign_orders WHERE id = $campaign_id");
	db_query("DELETE FROM campaign_orders_items WHERE parent_id = $campaign_id");
}