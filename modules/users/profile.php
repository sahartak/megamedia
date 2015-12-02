<?php
import('session');
import('person');
import('users');
import('party');
import('contacts');
import('forms');
import('roles');
import('products');
import('catalogs');

$mandatory = [
	'FIRST_NAME' => 'Contact First name',
	'LAST_NAME' => 'Contact Last name',
	'USER_LOGIN_ID' => 'Username',
	'CURRENT_PASSWORD' => 'Password',
	'NAME' => 'Company name',
	'STREET' => 'Street and number',
	'POSTAL' => 'Postal number',
	'CITY' => 'City',
	'CONTACT_EMAIL' => 'Contact email',
	'CONTACT_PHONE' => 'Contact phone',
	'PHONE' => 'Telephone',
	'ORDER_EMAIL' => 'Order email',
	// 'ORDER_FTP_ADDRESS' => 'FTP address',
	// 'ORDER_FTP_PASS' => 'FTP password',
	// 'ORDER_FTP_USER' => 'FTP username',
];

if (is_admin()) {
	if (!empty($_POST)) {
		// If admin then we set up a new user!
		$mandatory['PARTY_ID'] = 'ID';
		$mandatory['AFFILIATE_NAME'] = 'Handler name';
		$mandatory['AFFILIATE_DESCRIPTION'] = 'Handler phone';
		$mandatory['AFFILIATE_EMAIL'] = 'Handler email';

		// If everything is OK with the check we continue
		// otherwise there is a problem so we display the problems
		$_POST = forms_clean_data($_POST);
		list($check, $errors, $error_fields) = forms_check_mandatory($mandatory, $_POST);
		if ($check) {
			try {
				db_query('BEGIN;');

				// Saving primary data
				party_add($_POST['PARTY_ID'], PARTY_TYPE_COMPANY, $_SESSION['user']['PARTY_ID'], '');
				person_add($_POST['PARTY_ID'], '', $_POST['FIRST_NAME'], $_POST['LAST_NAME'], '', '', '', 0, 0, 0, 0, 0, '', 0, '');
				roles_assign_party($_POST['PARTY_ID'], ROLE_CLIENT);
				person_affiliate_add($_POST['PARTY_ID'], $_POST['AFFILIATE_NAME'], $_POST['AFFILIATE_DESCRIPTION'], $_POST['AFFILIATE_EMAIL']);

				// Saving login data
				users_add_login($_POST['USER_LOGIN_ID'], $_POST['CURRENT_PASSWORD'], $_POST['PARTY_ID'], USER_LOGIN_ENABLED);

				// Saving contacts
				// First we save the company contact and then we save the
				// contact persons contacts and finally the order
				// contacts
				contact_add($_POST['PARTY_ID'], COMPANY_CONTACT, array(
					CNT_ATTR_NAME => $_POST['NAME'],
					CNT_ATTR_STREET => $_POST['STREET'],
					CNT_ATTR_POSTAL => $_POST['POSTAL'],
					CNT_ATTR_CITY => $_POST['CITY'],
					CNT_ATTR_COUNTRY => $_POST['COUNTRY'],
					CNT_ATTR_PHONE => $_POST['PHONE'],
				));

				contact_add($_POST['PARTY_ID'], CONTACT_PERSON_CONTACT, array(
					CNT_ATTR_PHONE => $_POST['CONTACT_PHONE'],
					CNT_ATTR_EMAIL => $_POST['CONTACT_EMAIL'],
				));

				contact_add($_POST['PARTY_ID'], ORDER_CONTACTS, array(
					CNT_ATTR_EMAIL => $_POST['ORDER_EMAIL'],
					CNT_ATTR_FTP_ADDR => $_POST['ORDER_FTP_ADDRESS'],
					CNT_ATTR_FTP_USER => $_POST['ORDER_FTP_USER'],
					CNT_ATTR_FTP_PASS => $_POST['ORDER_FTP_PASS'],
				));

				// Setting product catalogs
				$prod_catalog_id = sprintf('%s-CATALOG', $_POST['PARTY_ID']);
				$prod_catalog_name = sprintf('%s %s Product catalog', $_POST['FIRST_NAME'], $_POST['LAST_NAME']);
				catalog_add($prod_catalog_id, $prod_catalog_name);
				$categories = products_get_categories(false);
				foreach ($categories as $_cat) {
					catalog_categories_add($prod_catalog_id, $_cat['PRODUCT_CATEGORY_ID']);
				}

				db_query('COMMIT;');

				// When everything is done redirect to the correct page
				header('location: /users/index?success=Client added successfully');
				exit;

			} catch (Exception $e) {
				db_query('ROLLBACK');
				$errors[] = $e->getMessage();
			}
		}
	}

	$customer = array();
	$user_login = array();
	$contact_attributes = array();
	$affiliate = array();
	$order_contacts_attributes = array();
	$contact_person = array();

} else {
	// We get the user that is currently logged in!
	if (!empty($_POST)) {
		// Saving clients data
		$_POST = forms_clean_data($_POST);

		if (isset($_SESSION['FAKE_LOGIN']) && $_SESSION['FAKE_LOGIN']) {
			$mandatory['AFFILIATE_NAME'] = 'Handler name';
			$mandatory['AFFILIATE_DESCRIPTION'] = 'Handler phone';
			$mandatory['AFFILIATE_EMAIL'] = 'Handler email';
		}

		list($check, $errors, $error_fields) = forms_check_mandatory($mandatory, $_POST);
		if ($check) {
			try {
				person_update_ltd($_SESSION['user']['PARTY_ID'], $_POST['FIRST_NAME'], $_POST['LAST_NAME'], '', '', '');
				users_update_login($_SESSION['user']['PARTY_ID'], $_POST['USER_LOGIN_ID'], $_POST['CURRENT_PASSWORD']);

				if (isset($_SESSION['FAKE_LOGIN']) && $_SESSION['FAKE_LOGIN']){
					person_affiliate_update($_SESSION['user']['PARTY_ID'], $_POST['AFFILIATE_NAME'], $_POST['AFFILIATE_DESCRIPTION'], $_POST['AFFILIATE_EMAIL']);
				}

				// Saving contacts
				contact_delete($_SESSION['user']['PARTY_ID'], COMPANY_CONTACT);
				contact_delete($_SESSION['user']['PARTY_ID'], CONTACT_PERSON_CONTACT);
				contact_delete($_SESSION['user']['PARTY_ID'], ORDER_CONTACTS);
				contact_add($_SESSION['user']['PARTY_ID'], COMPANY_CONTACT, array(
					CNT_ATTR_NAME => $_POST['NAME'],
					CNT_ATTR_STREET => $_POST['STREET'],
					CNT_ATTR_POSTAL => $_POST['POSTAL'],
					CNT_ATTR_CITY=> $_POST['CITY'],
					CNT_ATTR_COUNTRY => $_POST['COUNTRY'],
					CNT_ATTR_PHONE => $_POST['PHONE'],
				));

				contact_add($_SESSION['user']['PARTY_ID'], CONTACT_PERSON_CONTACT, array(
					CNT_ATTR_EMAIL => $_POST['CONTACT_EMAIL'],
					CNT_ATTR_PHONE => $_POST['CONTACT_PHONE'],
				));

				contact_add($_SESSION['user']['PARTY_ID'], ORDER_CONTACTS, array(
					CNT_ATTR_EMAIL => $_POST['ORDER_EMAIL'],
					CNT_ATTR_FTP_ADDR => $_POST['ORDER_FTP_ADDRESS'],
					CNT_ATTR_FTP_USER => $_POST['ORDER_FTP_USER'],
					CNT_ATTR_FTP_PASS => $_POST['ORDER_FTP_PASS'],
				));

				$success[] = 'Your profile has been updated';

			} catch (Exception $e) {
				$errors[] = $e->getMessage();
			}
		}

	}
	// Getting clients data
	$customer = person_get($_SESSION['user']['PARTY_ID']);
	$user_login = users_get_login($_SESSION['user']['PARTY_ID']);
	$affiliate = person_affiliate_get($_SESSION['user']['PARTY_ID']);

	$contacts = contact_mech_get($_SESSION['user']['PARTY_ID'], COMPANY_CONTACT);
	$contact_attributes = array();
	foreach ($contacts['attributes'] as $_attr) {
		$contact_attributes[$_attr['ATTR_NAME']] = $_attr['ATTR_VALUE'];
	}

	$contact_person_contacts = contact_mech_get($_SESSION['user']['PARTY_ID'], CONTACT_PERSON_CONTACT);
	foreach ($contact_person_contacts['attributes'] as $_attr) {
		$contact_person['CONTACT_' . $_attr['ATTR_NAME']] = $_attr['ATTR_VALUE'];
	}

	$order_contacts  = contact_mech_get($_SESSION['user']['PARTY_ID'], ORDER_CONTACTS);
	foreach ($order_contacts['attributes'] as $_attr) {
		$order_contacts_attributes['ORDER_' . $_attr['ATTR_NAME']] = $_attr['ATTR_VALUE'];
	}
}

$template = set_template('users', 'profile');
$link = THEME . 'template.php';
require_once($link);
