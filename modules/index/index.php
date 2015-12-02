<?php
import('forms');
import('users');
import('roles');
if(is_admin()){
	header('location: /test');
	exit;
}
if( !empty($_POST) ){
	if( trim($_POST['USER_LOGIN_ID']) == '' ){$errors[] = 'Please fill in username';}
	if( trim($_POST['CURRENT_PASSWORD']) == '' ){$errors[] = 'Please fill in password';}

	if( empty($errors) ){
		if( user_login($_POST['USER_LOGIN_ID'], $_POST['CURRENT_PASSWORD']) ){

			// if admin set the correct rights and redicrect to the correct page
			// otherwise threat as a normal user
			$roles = roles_get_party_role($_SESSION['user']['PARTY_ID']);
			if ($roles['ROLE_TYPE_ID'] == ROLE_ADMIN) {
				$_SESSION['user']['IS_ADMIN'] = true;
				$redirect_url = '/users/index';
			} else {
				$_SESSION['user']['IS_ADMIN'] = false;
				$redirect_url = '/dashboard/dashboard';
			}

			if( isset($_POST['REMEMBER_ME']) ){
				setcookie('RememberUser', $admin['PARTY_ID'],  time()+60*60*24*365, '/');
			}

			if( trim($_POST['REFERAL']) != '' ){
				$redirect_url = $_POST['REFERAL'];
				exit;
			}

			$_SESSION['user']['LAST_ACTION'] = time();
			user_login_history_add($_POST['USER_LOGIN_ID'], true, $_SESSION['user']['PARTY_ID']);
			header(sprintf("location: %s", $redirect_url));
			exit();
		}
		else{
			$errors[] = 'Invalid username and/or password';
			user_failed_login($_POST['USER_LOGIN_ID']);

			$party = user_get_login($_POST['USER_LOGIN_ID']);
			$party_id = !empty($party['PARTY_ID']) ? $party['PARTY_ID'] : '';
			user_login_history_add($_POST['USER_LOGIN_ID'], 0, $party_id);
		}
	}
}

$link = THEME . 'index.php';
require_once($link);
?>
