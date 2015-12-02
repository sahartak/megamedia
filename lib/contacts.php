<?php

// Types of contacts
define('COMPANY_CONTACT', 'COM-CNT');
define('ALTERNATIVE_CONTACT', 'ALT-CNT');
define('CONTACT_PERSON_CONTACT', 'CNT-PERSON-CNT');
define('ORDER_CONTACTS', 'ORDER-CNT');

// Contact attributes
define('CNT_ATTR_STREET' , 'STREET');
define('CNT_ATTR_POSTAL' , 'POSTAL');
define('CNT_ATTR_NAME'	 , 'NAME');
define('CNT_ATTR_COUNTRY', 'COUNTRY');
define('CNT_ATTR_CITY'	 , 'CITY');
define('CNT_ATTR_PHONE'	 , 'PHONE');
define('CNT_ATTR_EMAIL'	 , 'EMAIL');
define('CNT_ATTR_FTP_ADDR'	 , 'FTP_ADDRESS');
define('CNT_ATTR_FTP_USER'	 , 'FTP_USER');
define('CNT_ATTR_FTP_PASS'	 , 'FTP_PASS');

/**
 * Generates a contact id from the party id and
 * the contact type
 *
 * @param string $party_id the id of the party
 * @param string $contact_type the type of the contact
 * @return string the newly generated contact
 */
function contact_generate_id($party_id, $contact_type) {
	return sprintf("%s-%s", $party_id, $contact_type);
}

/**
 * Gets all contacts per party. Works with Company contacts
 * no alternatives
 *
 * @param string $party_id the id of the party
 * @param string $contact_type the type of the contact
 * @return array list with addresses
 */
function contact_get_by_party($party_id, $contact_type) {
	$id = contact_generate_id($party_id, $contact_type);

	$query = "SELECT ATTR_NAME, ATTR_VALUE
			  FROM contact_mech_attribute
			  WHERE CONTACT_MECH_ID = '".esc($id)."'";
	$data = db_query_to_array($query);

	return $data;
}

/**
 * Generates a well-formatted string with the address
 * from the address information
 *
 * @param array $contact_attributes all the attibutes of the address
 * @param  string $separator the separator for the lines
 * @return string the address line
 */
function contact_generate_string($contact_attributes, $separator = '<br />') {
	$return = array();
	$array = array(
		array(CNT_ATTR_NAME),
		array(CNT_ATTR_STREET),
		array(CNT_ATTR_POSTAL, CNT_ATTR_CITY),
		array(CNT_ATTR_COUNTRY)
	);

	foreach ($array as $_row) {
		$line = array();
		foreach ($_row as $_item) {
			foreach ($contact_attributes as $_attr) {
				if ($_attr['ATTR_NAME'] == $_item) {
					$line[] = $_attr['ATTR_VALUE'];
				}
			}
		}
		$return[] = implode(' ', $line);
	}

	return implode($separator, $return);
}

/**
 * Adds a new contact address to the database
 *
 * @param string $party_id the party to which the address belongs
 * @param string $contact_type_id the type od the address
 * @param array $attributes the attributes of the address separated
 * @return string the id of the new contact
 */
function contact_add($party_id, $contact_type_id, $attributes) {
	$id = contact_generate_id($party_id, $contact_type_id);

	if ($contact_type_id == ALTERNATIVE_CONTACT) {
		// Appending the next number
		$id = sprintf('%s-%d', $id, contact_alternative_count($id));
	}

	// Building the full address
	$full_address_arr = array();
	foreach ($attributes as $key => $val) {
		contact_attr_add($id, $key, $val);
		$full_address_arr[] = array('ATTR_NAME' => $key, 'ATTR_VALUE' => $val);
	}

	// Adding new contact mechanism
	$full_address = contact_generate_string($full_address_arr, "\n");
	$query = "INSERT INTO contact_mech (CONTACT_MECH_ID, CONTACT_MECH_TYPE_ID, INFO_STRING, CREATED_STAMP, CREATED_TX_STAMP)
			  VALUES ('$id', '$contact_type_id', '$full_address', '". now() ."', NOW())";
	db_query($query);

	return $id;
}

/**
 * Counts the address that begin with this rule
 *
 * @param string $id the beginning of the ids
 * @return integer the count of addresses that have this
 * beginning in their CONTACT_MECH_ID
 */
function contact_alternative_count($id) {
	$query = "SELECT COUNT(*)
			  FROM contact_mech
			  WHERE CONTACT_MECH_ID LIKE '$id%'";
	$data = db_query_to_row($query);

	return $data['COUNT(*)'];
}

/**
 * Gets a list of all alternative addresses per party
 *
 * @param string $party_id the id of the party
 * @return array list with addresses
 */
function contact_alternative_all($party_id) {
	$id = contact_generate_id($party_id, ALTERNATIVE_CONTACT);

	$query = "SELECT CONTACT_MECH_ID, INFO_STRING
			  FROM contact_mech
			  WHERE CONTACT_MECH_ID LIKE '$id%'
			  ORDER BY INFO_STRING ASC";
	return db_query_to_array($query);
}

/**
 * Adds a new attribute to the address line
 *
 * @param string $id the id of the contact mech
 * @param string $key the key of the attribute
 * @param string $val the actual value of the attribute
 * @return void
 */
function contact_attr_add($id, $key, $val) {
	$query = "INSERT INTO contact_mech_attribute (CONTACT_MECH_ID, ATTR_NAME, ATTR_VALUE, CREATED_STAMP, CREATED_TX_STAMP)
			  VALUES ('$id', '".esc($key)."', '".esc($val)."', '". now() ."', NOW())";
	db_query($query);
}

/**
 * Gets contact mech. Works with company address
 * and contact person address
 *
 * @param string $party_id id of the party that owns the contact
 * @param string $type the type of the contact mech
 * @return array the contact mech data with its attributes
 */
function contact_mech_get($party_id, $type) {
	$contact_mech_id = contact_generate_id($party_id, $type);
	$query = "SELECT CONTACT_MECH_ID, INFO_STRING
			  FROM contact_mech
			  WHERE CONTACT_MECH_ID = '".esc($contact_mech_id)."'";
	$data = db_query_to_row($query);

	if (!empty($data)) {
		$data['attributes'] = contact_get_by_party($party_id, $type);
	}

	return $data;
}

/**
 * Gets the attibutes of a contact
 *
 * @param string $contact_mech_id the id of the contact mech
 * @return array list with attribures and their values
 */
function contact_mech_get_attributes($contact_mech_id) {
	$query = "SELECT ATTR_NAME, ATTR_VALUE
			  FROM contact_mech_attribute
			  WHERE CONTACT_MECH_ID = '".esc($contact_mech_id)."'";
	return db_query_to_array($query);
}

function contact_mech_get_attribute($contact_mech_id, $attr_name) {
	$query = "SELECT ATTR_VALUE
			  FROM contact_mech_attribute
			  WHERE CONTACT_MECH_ID = '".esc($contact_mech_id)."' AND ATTR_NAME = '".esc($attr_name)."'";
	$data = db_query_to_row($query);

	return $data['ATTR_VALUE'];
}

/**
 * Deletes a contact and its attribiutes
 *
 * @param string $party_id the id of the client
 * @param string $contact_mech_type the type of the contact
 * @return void
 */
function contact_delete($party_id, $contact_mech_type) {
	$contact_mech_id = contact_generate_id($party_id, $contact_mech_type);

	$query = "DELETE FROM contact_mech_attribute WHERE CONTACT_MECH_ID = '$contact_mech_id'";
	db_query($query);

	$query = "DELETE FROM contact_mech WHERE CONTACT_MECH_ID = '$contact_mech_id'";
	db_query($query);
}

