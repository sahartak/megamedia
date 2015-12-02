<?php

/**
 *
 * Short for importing libraries
 * @param str $lib - the name of the library
 */
function import($lib){
	require_once LIB.$lib.'.php';
}

/**
 *
 * Returns the current datetime suitable for MySQL
 * @return str - current datetime
 */
function now(){
	return date('Y-m-d H:i:s');
}

/**
 *
 * Reformats dates based of the required new format
 * @param date $date - date string in any forms
 * @param str $format - format of the new date
 *
 * @return str - reformatted date
 */
function date_convert($date, $format){
	return date($format, strtotime($date));
}

/**
 * Gets the system settings from the database
 *
 * @return array list with system settings
 * the KEY column becomes the array key and
 * the VALUE column becomes the corresponding value
 * for that key
 */
function get_settings() {
	$return = [];

	$query = "SELECT * FROM system_settings";
	$data = db_query_to_array($query);
	if (!empty($data)) {
		foreach ($data as $_row) {
			$return[$_row['KEY']] = $_row['VALUE'];
		}
	}

	return $return;
}

/**
 * Sets the value for a system variable
 *
 * @param string $key the key of the setting
 * @param string $value the value of the setting
 */
function set_setting($key, $value) {
	$query = "UPDATE system_settings
			SET VALUE = '".esc($value)."' WHERE `KEY` = '".esc($key)."'
			LIMIT 1";
	db_query($query);
}

/**
 * Sets the name of the template for the view
 *
 * @param string $module the name of the module
 * @param string $template the name of the template file
 * @return string the full name of the template
 */
function set_template($module, $template) {
	return $module . DIRECTORY_SEPARATOR . $template;
}

/**
 * Checks whether a user is an admin
 *
 * @return boolean true user is admin false it is not
 */
function is_admin() {
	return isset($_SESSION['user']['IS_ADMIN']) && $_SESSION['user']['IS_ADMIN'];
}

/**
 * Checks whether the date has a valid format for displaying
 *
 * @param date $date date to be checked
 * @return boolean true if the date can be displayed, false if not
 */
function is_valid_date($date) {
	$date = date_convert($date, DEFAULT_DATE_FORMAT);
	return $date != '' && $date != '01.01.1970' && $date != '30.11.-0001';
}

/**
 * Get variable from $_POST by key
 *
 * @param string $key
 * @return $_POST[$key] if isset, '' if not
 */
function getVar($key) {
	return isset($_POST[$key]) ? $_POST[$key] : '';
}

/**
 * Get variable from $_GET by segment
 *
 * @param integer $n
 * @return $_GET[$n] if isset, '' if not
 */
function get_url_param($n){
	$array = explode("/",$_GET['url']);
	return isset($array[$n]) ? $array[$n] : '';
}
