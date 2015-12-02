<?php
/**
 * This files contains all functions related to the people as person
 * in the system
 */

//Defines the customers types
define('ACTIVE'  , 'ACTIVE');
define('INACTIVE', 'INACTIVE');

/**
 *
 * Gets person data by party id
 * @param str $party_id - party id of the person
 * @return arr - with data about the person
 */
function person_get($party_id){
	$query = "SELECT FIRST_NAME, LAST_NAME, COMPANY, COMMENTS, BILLING_PERIOD, PAYMENT_TERMS, YEARLY_REFUND, BILLING_COMPANY, SALUTATION, OCCUPATION, SATURDAY_DELIVERY, PROFILE_IMAGE,
					 PARTY_ID, BILLING_CURRENCY, BILLING_ADDRESS, PAYMENT_METHOD, PURPOSE
			  FROM person
			  WHERE PARTY_ID='".esc($party_id)."'
			  LIMIT 1";
	return db_query_to_row($query);
}

/**
 *
 * Changes the status of a person
 * @param str $party_id   - id of the person
 * @param str $new_status - the new status
 */
function person_status_update($party_id, $new_status){
	$query = "UPDATE person
			  SET STATUS_ID = '".esc($new_status)."'
			  WHERE PARTY_ID = '".esc($party_id)."'
			  LIMIT 1";
	db_query($query);
}

/**
 *
 * Adds a new person to the database with the following information
 * @param str $party_id 		- party id
 * @param str $salut 			- salutation
 * @param str $first_name 		- first name
 * @param str $last_name 		- last name
 * @param str $occupation 		- occupation / function
 * @param str $company 			- company
 * @param str $comments 		- comments related to the person
 * @param int $billing_period 	- the billing period in days
 * @param int $payment_terms  	- the paymnets perion in days
 * @param float $yearly_refund 	- the refund percent per yera
 * @param str $billing_company 	- the name of the company that receives the invoices
 * @param int $saturday 		- does the customer is allowed to have saturday deliveries ( 1 - yes, 0 - no )
 * @param str $billing_currency - the default curreny of the invoices that the customer will get
 * @param int $billing_address  - shows whether the billing address of a customer is on ( 1 - yes, 0 - no )
 * @param str $purpose			- the purpose of the registration action
 */
function person_add($party_id, $salut, $first_name, $last_name, $occupation, $company, $comments, $billing_period, $payment_terms, $yearly_refund, $billing_company, $saturday,
					$billing_currency, $billing_address, $purpose){
	$query = "INSERT INTO person (PARTY_ID, SALUTATION, FIRST_NAME, LAST_NAME, OCCUPATION, COMPANY, CREATED_STAMP, CREATED_TX_STAMP, COMMENTS, BILLING_PERIOD, PAYMENT_TERMS,
								  YEARLY_REFUND, BILLING_COMPANY, SATURDAY_DELIVERY, BILLING_CURRENCY, BILLING_ADDRESS, PURPOSE)
			  VALUES ('$party_id', '".esc($salut)."', '".esc($first_name)."', '".esc($last_name)."', '".esc($occupation)."', '".esc($company)."', '".now()."', NOW(), '".esc($comments)."'
			  		, '".esc($billing_period)."', '".esc($payment_terms)."', '".esc($yearly_refund)."', '".esc($billing_company)."', '".esc($saturday)."', '".esc($billing_currency)."',
			  		  '".esc($billing_address)."', '".esc($purpose)."')";
	db_query($query);
}

/**
 *
 * Updates a customers profile with a limited affect for the customer space
 * @param str $party_id		- id of the customer
 * @param str $first_name   - first name
 * @param str $last_name	- last name
 * @param str $company		- company of the customer
 * @param str $salutation   - salutation of the customer
 * @param str $occupation	- occupation of the customer withing the company
 */
function person_update_ltd($party_id, $first_name, $last_name, $company, $salutation, $occupation){
	$query = "UPDATE person
			  SET FIRST_NAME = '".esc($first_name)."', LAST_NAME='".esc($last_name)."', LAST_UPDATED_STAMP = '".now()."', LAST_UPDATED_TX_STAMP = NOW(), COMPANY = '".esc($company)."',
			  	  SALUTATION = '".esc($salutation)."', OCCUPATION='".esc($occupation)."'
			  WHERE PARTY_ID='".esc($party_id)."'
			  LIMIT 1";
	db_query($query);
}

/***************************************
 * AFFILIATE FUNCTIONS
 * *************************************/

/**
 * Gets the megamedia person in charge of a customer
 *
 * @param string $party_id the id of the customer
 * @return array with in charge person data
 */
function person_affiliate_get($party_id) {
	$query = "SELECT AFFILIATE_NAME, AFFILIATE_DESCRIPTION, AFFILIATE_EMAIL
			  FROM affiliate
			  WHERE PARTY_ID = '".esc($party_id)."'
			  LIMIT 1";
	return db_query_to_row($query);
}

/**
 * Adds a new affiliation in the databse
 *
 * @param string $party_id the party id of the customer
 * @param string $name the name of the affilation
 * @param string $phone the description of the affiliation
 * @param string $email the phone of the affiliation
 * @return void
 */
function person_affiliate_add($party_id, $name, $phone, $email) {
	$query = "INSERT INTO affiliate (PARTY_ID, AFFILIATE_NAME, AFFILIATE_DESCRIPTION, AFFILIATE_EMAIL, CREATED_STAMP, CREATED_TX_STAMP)
			  VALUES ('".esc($party_id)."', '".esc($name)."', '".esc($phone)."', '".esc($email)."','".now()."', NOW())";
	db_query($query);
}

/**
 * Updates an existing affiliation in the databse
 *
 * @param string $party_id the party id of the customer
 * @param string $name the name of the affilation
 * @param string $phone the description of the affiliation
 * @param string $email the phone of the affiliation
 * @return void
 */
function person_affiliate_update($party_id, $name, $phone, $email) {
	$query = "UPDATE affiliate
			  SET AFFILIATE_NAME = '".esc($name)."' ,
			  	  AFFILIATE_DESCRIPTION = '".esc($phone)."',
			  	  AFFILIATE_EMAIL = '".esc($email)."',
			  	  LAST_UPDATED_STAMP = '".now()."',
			  	  LAST_UPDATED_TX_STAMP = NOW()
			  WHERE PARTY_ID = '".esc($party_id)."'
			  LIMIT 1";
	db_query($query);
}
