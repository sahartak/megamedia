<?php
import('session');
import('forms');

if (!is_admin()) {
	header('location: /users/logut');
	exit;
}

if (isset($_POST) && !empty($_POST)) {
	foreach ($_POST as $key => $value) {
		set_setting($key, $value);
	}
	$success[] = 'New settings saved!';
	$system_settings = get_settings();
}

$template = set_template('dashboard', 'parameters');
$link = THEME . 'template.php';
require_once($link);
