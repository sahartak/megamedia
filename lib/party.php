<?php

define('PARTY_TYPE_COMPANY', 'COMPANY');

/**
 *
 * Checks if a party exists in the database
 * @param str $party_id - id of the party that is being checked
 * @return bool T/F
 */
function party_exists($party_id){
	$query = "SELECT COUNT(*) FROM party WHERE PARTY_ID='".esc($party_id)."'";
	$data  = db_query_to_row($query);
	return $data['COUNT(*)'] > 0;
}

/**
 *
 * Adds new party to the database
 * @param str $type - type of te party
 * @param str $user_login_id - login of the person who created the party
 *
 * @return arr - the new party
 */
function party_add($id, $type, $user_login_id, $data_source_id){
	$return = false;

	if (!party_exists($id)) {
		$query = "INSERT INTO party (PARTY_ID, PARTY_TYPE_ID, CREATED_DATE, CREATED_BY_USER_LOGIN, DATA_SOURCE_ID, CREATED_STAMP, CREATED_TX_STAMP)
				  VALUES ('$id', '".esc($type)."', NOW(), '".esc($user_login_id)."', '".esc($data_source_id)."',  '".now()."', NOW())";
		db_query($query);
		$return = $id;
	} else {
		throw new RuntimeException('A client witht his ID already exists');
	}

	return $return;
}
