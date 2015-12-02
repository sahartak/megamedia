<?php
if(isset($_SESSION['order_id'])) {
	$order_id = $_SESSION['order_id'];
	unset($_SESSION['order_id']);
	db_query('DELETE FROM orders WHERE id = '.$order_id);
	$ids = db_query_to_list('SELECT id FROM campaign_orders WHERE order_id = '.$order_id, 'id');
    $ids_str = '';
    if($ids)
		$ids_str = implode(',', $ids);
	db_query('DELETE FROM campaign_orders WHERE order_id = '.$order_id);
    if($ids_str)
		db_query('DELETE FROM campaign_orders_items WHERE parent_id IN ('.$ids_str.')');
	db_query('DELETE FROM additional_orders_items WHERE order_id = '.$order_id);
}
header('location: /');
exit;