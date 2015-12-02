<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

session_start();
mb_internal_encoding('UTF-8');
date_default_timezone_set('UTC');
set_time_limit(0);

define('WEBSITE', 'Megamedia Calculator');

define('ROOT'			, $_SERVER['DOCUMENT_ROOT'].'/');
define('MODULES'		, ROOT.'modules/');
define('THEMES'			, ROOT.'themes/');
define('LIB'			, ROOT.'lib/');
define('CLASSES'		, ROOT.'classes/');
define('EMAIL_TAMPLATES', ROOT.'email/templates.php');

define('CLIENT_B2B'     , strtoupper(array_shift((explode(".", $_SERVER['HTTP_HOST'])))));
define('THEME_FOLDER'	, 'themes');
define('THEME'			, THEMES.'harald/');
define('THEME_NAME'     , basename(THEME));

define('ADMIN_PATH', 'admin');
define('ADMIN_THEME', THEMES.'admin/');
define('CLIENT_THEME', THEMES . '/');

define('CLIENT_HORECA', 'HORECA');
define('CLIENT_AT_HOME', 'AT_HOME');

define('DEFAULT_LANG'        , 'en');
define('DEFAULT_CURRENCY'    , 'DKK');
define('DEFAULT_DATE_FORMAT' , 'd.m.Y');
define('SUPPLIER_TAX_COUNTRY', 'DK'); //For every other country there is not tax in the order

define('ADMIN_SESSION_IDLE_TIME', 30*60);
define('CLIENT_SESSION_IDLE_TIME', 30*60);

define('ROWS_PER_PAGE', 30);
define('IMAGES_PER_PRODUCT', 4);
define('ROWS_PER_CALCULATION', 1); // The number of rows with motives in the calcuator input

define('MYSQL_DATE_FORMAT', 'Y-m-d');

define('IMAGES_PATH', ROOT.'uploads/images/');
define('IMAGES_DISPLAY_PATH', '/uploads/images/');
define('FILES_PATH', ROOT.'uploads/files/');
define('FILES_DISPLAY_PATH', '/uploads/files/');
define('PDFS_PATH', ROOT.'uploads/pdfs/');

define('ORDER_ALERT_DAYS', 14);
define('DELETED', 'DELETED'); //Defines a deleted status in all entities

//Types of orders
define('PURCHASE_ORDER'   , 'BUY');  //Defines a type of order that the enterprise places to suppliers
define('SELLING_ORDER'    , 'SELL'); //Defines a type of order that the enterprise receives from customers
define('FREE_GOODS_ORDER' , 'FREE'); //Defines a type of order that the enterprise gives the customers free goods
define('TEMP_ORDER'       , 'TEMP'); //Defines a type of order that is temporarary and can be validated

//Types of invoices
define('ORDER_INVOICE', 'ORDER_INVOICE'); //Invoice to a normal customer order
define('FREE_INVOICE' , 'FREE_INVOICE');   //Invoice on a FREE GOODS / CREDIT NOTE

//Invoices statuses
define('INV_CREATED'	, 'INV_CREATED');
define('INV_APPROVED'	, 'INV_APPROVED');
define('INV_PART_PAID'	, 'INV_PART_PAID');
define('INV_PAID'		, 'INV_PAID');
define('INV_CANCELLED'  , 'INV_CANCELLED');

//User stuff
define('HOST', $_SERVER['HTTP_HOST']);

//Define reminders step
define('CE_REMINDER_NUMBER'  ,  3); //Defines the number of reminders per invoice
define('CE_REMINDER_INTERVAL', 20); //Defines the reminder steps in number of days

$langs = array();
$dict  = array();
$salutations = array('Mr', 'Mrs', 'Mr & Mrs', 'No title');
$order_alert_days = array();

//The billing periods and the payment terms indexes cannot be zero because the database fields are INT(11)
//Having a 0 index will result in an warning/notification in the invoices-view script where billing currency
//and payment terms settings are checked before printing an invoice. Therefore to have a 0 days payment terms
//and billing period we need to index them in a different way
$billing_periods = array('999' => '0 days', '15'=>'15 Days', '30'=>'30 Days');
$payment_terms 	 = array('999' => '0 days', '10'=>'10 Days', '30'=>'30 Days', '45'=>'45 Days');

//Defining cash PAYMENT_TEMS
define('PAYMENT_TERM_ZERO', 999);

$tax_rates		 = array('0' => '0', '2.5'=>'2.5', '8'=>'8');
$customers_images= array(270, 0);
$product_images  = array(
							'small'   => array(100, 100),
							'medium'  => array(200, 200),
							'large'   => array(400, 400),
							'detail'  => array(800, 800),
						);
$work_effort_statuses = array();
$work_effort_priorities = array();

//Admin lists configuration
$full_screen_modules 	 = array('users', 'products', 'index', 'payments', 'orders','test','store');
$full_screen_controllers = array('admin/customers', 'admin/products', 'admin/suppliers', 'admin/dashboard', 'admin/work-effort-list', 'admin/statistics-view', 'admin/payments',
								 'admin/customer-orders', 'admin/production-report', 'admin/products-listing','test/index');

//Reminder colours
$reminder_colors = array('1' => 'success',
						 '2' => 'warning',
						 '3' => 'important');

//Types of payment methods
$payment_details_methods = array('ONLINE' => 'Direct Online', 'INVOICE' => 'Invoice', 'CASH' => 'Cash');

//Suppliers Orders feedback values the values are displayed up
//to the third symbol in the dropdowns throughout the system
$suppliers_orders_feedback = array();

//The email that will receive all the messages from the system
define('ADMIN_EMAIL'	, '');
define('ORDER_EMAIL'	, '');
define('NEW_USER_EMAIL'	, '');
define('CUSTOMER_EMAIL' , '');
define('SENT_FROM'  	, '');
define('REPLY_TO'   	, '');

// The PARTY_ID of the enterprice that owns the
// web store
define('ENTERPRICE_PARTY_ID', 'MM');
