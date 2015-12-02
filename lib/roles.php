<?php

define('ROLE_CLIENT' , 'CLIENT');
define('ROLE_ADMIN' , 'ADMIN');

/**
 *
 * Gets the current role of a party
 * @param str $party_id - id of the party
 * @return arr - current party role
 */
function roles_get_party_role($party_id){
	$query = "SELECT  pr.PARTY_ID, rt.ROLE_TYPE_ID, rt.DESCRIPTION
			  FROM `party_role` pr JOIN role_type rt
			  ON pr.ROLE_TYPE_ID = rt.ROLE_TYPE_ID
			  WHERE pr.PARTY_ID = '".esc($party_id)."'";
	return db_query_to_row($query);
}

/**
 *
 * Assigns a role to a party
 * @param str - party id
 * @param str - role type
 */
function roles_assign_party($party_id, $role_type){
	$query = "INSERT INTO party_role (PARTY_ID, ROLE_TYPE_ID, CREATED_STAMP, CREATED_TX_STAMP)
			  VALUES ('$party_id', '".esc($role_type)."', '".now()."', NOW())";
	db_query($query);
}
