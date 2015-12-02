<?php
import('person');

define('USER_LOGIN_ENABLED', 'A');

/*****************************************************
 *
 * LOGIN FUNCTIONS
 *
 *
 ****************************************************/
/**
 *
 * Checks whether a customer is logged in the customer space
 * @return bool T/F
 */
function user_is_logged(){
	$return = isset($_SESSION['user']['last_action']) && (time() - $_SESSION['user']['last_action'] < CLIENT_SESSION_IDLE_TIME);

	if($return){
		$_SESSION['user']['last_action'] = time();
	}

	return $return;
}

/**
 * Logs an user into the system
 * @param str $email    - the email of the customer
 * @param str $password - the password of the customer
 * @return bool - T/F
 */
function user_login($email, $password){
	$return = false;

	$query = "SELECT COUNT(*)
			  FROM user_login ul JOIN person pe ON ul.PARTY_ID = pe.PARTY_ID
			  WHERE USER_LOGIN_ID='".esc($email)."' AND CURRENT_PASSWORD='".esc($password)."' AND ENABLED = 'A'
			  AND pe.STATUS_ID != '".DELETED."'";
	$data = db_query_to_row($query);

	if( $data['COUNT(*)'] == 1 ){
		$return = true;
		$user_login = user_get_login($email);
		$person 	= person_get($user_login['PARTY_ID']);

		$_SESSION['user'] = array();
		$_SESSION['user']['SALUTATION']     = $person['SALUTATION'];
		$_SESSION['user']['FIRST_NAME']     = $person['FIRST_NAME'];
		$_SESSION['user']['LAST_NAME']      = $person['LAST_NAME'];
		$_SESSION['user']['PARTY_ID']       = $person['PARTY_ID'];
		$_SESSION['user']['USER_LOGIN_ID']  = $email;
		$_SESSION['user']['last_action']    = time();
	}

	return $return;
}

/**
 * Logs an user into the system
 * @param str $email    - the email of the customer
 * @param str $password - the password of the customer
 * @return bool - T/F
 */
function user_login_new($email, $password){
	$return = false;

	$query = "SELECT *  FROM users WHERE login='".esc($email)."' AND password='".esc($password)."' ";
	$data = db_query_to_row($query);

	if( isset($data['id']) ){
		$return = true;
		$_SESSION['user'] = array();
		$_SESSION['user']['login'] = $data['login'];
		$_SESSION['user']['id']    = $data['id'];
		$_SESSION['user']['FIRST_NAME']  = $data['first_name'];
		$_SESSION['user']['LAST_NAME']   = $data['last_name'];
	}
	return $return;
}

/**
 *
 * Records a failed login attemp by an system user
 * @param str $user_login_id - login id of the
 */
function user_failed_login($user_login_id){
	$query = "UPDATE user_login
			  SET SUCCESSIVE_FAILED_LOGINS = SUCCESSIVE_FAILED_LOGINS + 1
			  WHERE USER_LOGIN_ID = '".esc($user_login_id)."'
			  LIMIT 1";
	db_query($query);
}

/**
 *
 * Gets user login information based on customers email
 * @param str $email - the email of the customer
 * @return arr - data from the database table user_login
 */
function user_get_login($email){
	$query = "SELECT PARTY_ID
			  FROM user_login
			  WHERE USER_LOGIN_ID = '".esc($email)."'
			  LIMIT 1";
	return db_query_to_row($query);
}

/**
 *
 * Gets a list of customers
 * @param int $page     - number of page
 * @param str $order_by - the column to be ordered by
 * @param str $asc      - the orientation of the sorting
 * @return arr - list wiht clients
 */
function users_get_clients($page, $order_by, $asc){
	if($order_by == ''){ $order_by = 'pe.FIRST_NAME'; }
	if($order_by == 'pe.PARTY_ID'){ $order_by = 'CAST( SUBSTR(pe.PARTY_ID FROM 3) as SIGNED INTEGER)'; }
	if($asc == ''){ $asc = 'ASC'; }

	$query = "SELECT p.DATA_SOURCE_ID,
					 pe.PARTY_ID, pe.COMPANY, pe.FIRST_NAME, pe.LAST_NAME, pe.STATUS_ID,
					 ul.ENABLED
			  FROM party p JOIN person pe JOIN user_login ul JOIN party_role pr
			  ON p.PARTY_ID = pe.PARTY_ID AND pe.PARTY_ID = ul.PARTY_ID AND pr.PARTY_ID = p.PARTY_ID
			  WHERE pr.ROLE_TYPE_ID = '".ROLE_CLIENT."' AND pe.STATUS_ID != '".DELETED."'
			  ORDER BY $order_by $asc";
	if($page){
		$query .= db_get_limit($page);
	}

	return db_query_to_array($query);
}


/**
 *
 * Adds new user login information
 * @param str $email - email to log in with
 * @param str $password - password to log in with
 * @param str $party_id - corresponding party
 * @param str $enabled  - whether the can log in within the system
 */
function users_add_login($email, $password, $party_id, $enabled){
	$exists = user_get_login($email);
	if (!empty($exists)) {
		throw new RuntimeException(sprintf('There is user with the same login: %s', $email));
	}

	$query = "INSERT INTO user_login (USER_LOGIN_ID, CURRENT_PASSWORD, ENABLED, HAS_LOGGED_OUT, DISABLED_DATE_TIME, SUCCESSIVE_FAILED_LOGINS, CREATED_STAMP, CREATED_TX_STAMP, PARTY_ID)
			  VALUES ('".esc($email)."', '".esc($password)."', '$enabled', 'N', NOW(), 0, '".now()."', NOW(),'$party_id')";
	db_query($query);
}

/**
 *
 * Gets user login information by party id
 * @param str $party_id - party id of the user
 * @return array - data about the user login
 */
function users_get_login($party_id){
	$query = "SELECT USER_LOGIN_ID, CURRENT_PASSWORD FROM user_login WHERE party_id='".esc($party_id)."'";
	$data  = db_query_to_row($query);
	return $data;
}

/**
 *
 * Updates the login information about a user
 * @param str $party_id - party id of the user
 * @param str $login_id - new user login id
 * @param str $password - new password
 */
function users_update_login($party_id, $login_id, $password){
	$exists = user_get_login($login_id);
	if (!empty($exists) && $exists['PARTY_ID'] != $party_id) {
		throw new RuntimeException(sprintf('There is user with the same login: %s', $login_id));
	}

	$query = "UPDATE user_login
			  SET USER_LOGIN_ID = '".esc($login_id)."', CURRENT_PASSWORD='".esc($password)."', LAST_UPDATED_STAMP = '".now()."', LAST_UPDATED_TX_STAMP = NOW()
			  WHERE PARTY_ID = '".esc($party_id)."'
			  LIMIT 1";
	db_query($query);
}

/*****************************************************
 *
 * LOGIN HISTORY FUNCTIONS
 *
 *
 ****************************************************/

/**
 * Adds a record to the login history
 *
 * @param string $user_login_id the user login used
 * @param integer $success whether the login was successfull
 * @param string $party_id the party id of the users if such exists
 */
function user_login_history_add($user_login_id, $success, $party_id) {
	$query = "INSERT INTO user_login_history (USER_LOGIN_ID, DATETIME, SUCCESS, PARTY_ID, IP, SITE)
			  VALUES ('".esc($user_login_id)."', NOW(), '".esc($success)."', '".esc($party_id)."', '".esc($_SERVER['REMOTE_ADDR'])."', '".HOST."')";
	db_query($query);
}
