<?php

/**
 * Checks if the session has expired
 *
 * @return bool T/F
 */
function session_expired(){
	$return = true;

	if( isset($_SESSION['user']['LAST_ACTION']) ){
		$return = ( time() - ( $_SESSION['user']['LAST_ACTION'] + CLIENT_SESSION_IDLE_TIME ) ) > 0;
	}

	return $return;
}

if( session_expired() ){
	// import('party');
	// import('admin');
	// import('users');

	header('location: /');
	exit;

	// $remember_me = isset($_COOKIE['RememberAdmin'])?$_COOKIE['RememberAdmin']:false;
	// if( $remember_me !== false ){
	// 	if(party_exists($remember_me) && is_admin($remember_me)){
	// 		$admin = users_get_login($remember_me);
	// 		admin_login($admin['USER_LOGIN_ID']);
	// 	}
	// 	else{
	// 		header($path);
	// 		exit();
	// 	}
	// }
	// else{
	// 	admin_logout('');
	// 	header($path);
	// 	exit();
	// }
}
else{
	$_SESSION['user']['LAST_ACTION'] = time();
}
