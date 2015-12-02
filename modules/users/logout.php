<?php
if (isset($_SESSION['FAKE_LOGIN'])) {
	$client = users_get_login(ENTERPRICE_PARTY_ID);
	user_login($client['USER_LOGIN_ID'], $client['CURRENT_PASSWORD']);
	$_SESSION['user']['IS_ADMIN'] = true;
	$_SESSION['user']['LAST_ACTION'] = time();
	unset($_SESSION['FAKE_LOGIN']);
	header('location: /users/index');
	exit;

} else {

	session_destroy();
	unset($_SESSION['CART']);
	header('location: /index/login');
	exit;

}
