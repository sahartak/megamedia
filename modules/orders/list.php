<?php
import('session');
import('orders');
import('forms');

$page = forms_get_page();
$orders = orders_get($page, [ORDER_APPROVED, ORDER_OFFER], ORDER_NORMAL, $_SESSION['user']['PARTY_ID'], false, false, false);

$template = set_template('orders', 'list');
$link = THEME . 'template.php';
require_once($link);
