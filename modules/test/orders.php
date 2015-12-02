<?php
if(!isset($_SESSION['user'])){
	header('location: /');
	exit;
}
import('pagination');
import('checkout');

$pagination = array();
$orders = get_all_orders($pagination);

$template = set_template('test', 'orders');
$link = THEME . 'template2.php';

require_once($link);