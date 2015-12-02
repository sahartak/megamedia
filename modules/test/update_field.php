<?php
if((!$order_id = $_SESSION['order_id']) || !isset($_POST['field']) || !isset($_POST['value']) || !$_POST['value'] || !isset($_POST['id']))
	die('no order');

$id = abs((int)$_POST['id']);

if($id) {
	$result = db_query_to_row("SELECT 1 FROM additional_orders_items WHERE id = $id AND order_id = $order_id");
	if(!empty($result)) {
		$field = $_POST['field'];
		if(in_array($field, ['width', 'height', 'amount'])) {
			$value = abs((int)$_POST['value']);
		} elseif(in_array($field, ['hanging', 'material'])) {
			if($field == 'hanging')
				$field = 'ophaeng_id';
			else
				$field = 'material_id';
			$value = abs((int)$_POST['value']);
		} elseif($field == 'week') {
			$field = 'week_number';
			$value = htmlspecialchars(trim($_POST['value']));
		}
		if($value) {
			update_in_db('additional_orders_items', [$field => $value], "id = $id AND order_id = $order_id");
		}
	}

}
