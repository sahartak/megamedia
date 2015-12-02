<?php
if(!isset($_SESSION['user'])){
	header('location: /');
	exit;
}
$template = set_template('test', 'index');
$link = THEME . 'template2.php';

require_once($link);