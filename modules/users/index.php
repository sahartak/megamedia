<?php
import('session');
import('forms');
import('users');
import('party');
import('person');

if (!is_admin()) {
	header('location: /users/logut');
	exit;
}

/**
 * If the admin wants to visit the homepage of the client
 * then we use the VIEW otherwise the admin is visiting the
 * profile of the client which is the profile VARIABLE
 */
if (
		(isset($_GET['VIEW']) && !empty($_GET['VIEW'])) ||
		(isset($_GET['PROFILE']) && !empty($_GET['PROFILE']))
	) {
	// Login as the user
	if (isset($_GET['VIEW'])) {
		$client_id = $_GET['VIEW'];
		$redirect_url = '/dashboard/dashboard';
	} else {
		$client_id = $_GET['PROFILE'];
		$redirect_url = '/users/profile';
	}

	$_SESSION['FAKE_LOGIN'] = $_SESSION['user']['PARTY_ID'];
	$client = users_get_login($client_id);
	user_login($client['USER_LOGIN_ID'], $client['CURRENT_PASSWORD']);
	$_SESSION['user']['IS_ADMIN'] = false;
	$_SESSION['user']['LAST_ACTION'] = time();
	header(sprintf('location: %s', $redirect_url));
	exit;
}

if (isset($_GET['DELETE']) && !empty($_GET['DELETE'])) {
	if (!party_exists($_GET['DELETE'])) {
		$errors[] = sprintf('Customer with id %s does not exist', $_GET['DELETE']);
	} else {
		$person = person_get($_GET['DELETE']);
		person_status_update($_GET['DELETE'], DELETED);
		$success[] = sprintf('Customer: %s %s has been marked as deleted', $person['FIRST_NAME'], $person['LAST_NAME']);
	}
}

$page = forms_get_page();
$customers = users_get_clients($page, 'pe.FIRST_NAME', 'ASC');

$template = set_template('users', 'index');
$link = THEME . 'template.php';
require_once($link);
