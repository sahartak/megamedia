<?php
/**
 *
 * Gets the value of a post index
 * @param str $index - index key from the post array
 * @return str - the value of the index
 */
function forms_post($index){
	return isset($_POST[$index])?$_POST[$index]:'';
}

/**
 *
 * Gets the value of a get index
 * @param str $index - index key from the get array
 * @return str - the value of the index
 */
function forms_get($index){
	return isset($_GET[$index])?$_GET[$index]:'';
}

/**
 *
 * Displays error messages in admin part if such exist
 * @param arr $errors - containing all error messages
 * @param str $class - the class for the box could be success
 * @return str - HTML code for the errors
 */
function forms_admin_errors($errors, $class = "error"){
	$return = '';
	$count = count($errors);

	if($count){
		$return = '<div class="alert alert-'.$class.'">';
		for($i=0; $i<$count; ++$i){
			$return .= $errors[$i].'<br />';
		}

		$return .= '</div>';
	}

	return $return;
}

/**
 *
 * Displays success messages in admin part if such exist
 * @param arr $success - containing all successs messages
 * @return str - HTML code for the errors
 */
function forms_admin_success($success){
	return forms_admin_errors($success, 'success');
}

/**
 *
 * Draws a select in a form. If an array elements is not an array itself
 * The label will become an optgroup
 * @param arr $data - array with data to be used for filling oprions
 * @param str $value_field - index of array to get the value in the option
 * @param str $info_field - index of array to get the human text
 * @param sre $default - the default value to be selected
 * @param str $append  - text to replace "Please Select"
 * @return str HTML code for select
 */
function forms_draw_select($data, $value_field, $info_field, $default, $append = ''){
	global $dict;

	$return = '';
	if ($append !== false) {
		$text   = trim($append) != ''?$append:$dict['please_select'];
		$return = '<option value="">'.$text.'</option>';
	}

	foreach($data as $_row){
		if( is_array($default) && !empty($default) ){
			$selected = in_array($_row[$value_field], $default) == $default?'selected':'';
		}
		else{
			$selected = isset($_row[$value_field]) && $_row[$value_field] == $default?'selected':'';
		}

		if( is_array($info_field) ){
			$human_arr = array();
			foreach($info_field as $_field){
				$human_arr[] = $_row[$_field];
			}
			$human_text = implode(' ', $human_arr);
		}
		else{
			$human_text = isset($_row[$info_field])?$_row[$info_field]:'';
		}

		if( !is_array($_row) ){
			$return .= '<optgroup label="'.$_row.'">';
		}
		else{
			$return .= '<option '.$selected.' value="'.$_row[$value_field].'">'.ucwords($human_text).'</option>';
		}
	}

	return $return;
}

/**
 *
 * Gets the current page
 * @return int the number of the current page
 */
function forms_get_page(){
	return isset($_GET['page']) && (int)$_GET['page'] > 0?(int)$_GET['page']:1;
}

/**
 *
 * Enter description here ...
 * @param arr $data - array with data to be searched
 * @param str $index - index to search for
 * @return ste value
 */
function forms_post_or_data($data, $index){
	if( !empty($_POST) && isset($_POST[$index]) ){
		$return = forms_post($index);
	}
	else{
		$return = isset($data[$index])?$data[$index]:'';
	}

	return $return;
}

/**
 * Checks if all the mandatory fields are filled in
 *
 * @param array $mandatory list with mandatory fields
 * the key corresponds to the data and the value is the
 * human understandable name of the key
 * @param array $data to be check against the mandatory
 * array the key corresponds to the $mandatory key and
 * the value is the actual value filled in by the user
 * @return array (boolean, array) the result of the opperation
 * true if everyuthing is OK false if something went wrong
 * the second array contains the errors
 */
function forms_check_mandatory($mandatory, $data) {
	$errors = array();
	$success = true;
	$fields = array();
	$mandatoryKeys = array_keys($mandatory);

	foreach($data as $_key => $_val) {
		if (in_array($_key, $mandatoryKeys)) {
			if (trim($_val) == '') {
				$errors[] = sprintf('Field: %s is mandatory', $mandatory[$_key]);
				$fields[] = $_key;
				$success = false;
			}
		}
	}

	return array($success, $errors, $fields);
}

/**
 *
 * Cleans the data from not needed information like trailing spaces
 * @param arr $data - arr with data to be cleaned
 * @return arr - cleaned array
 */
function forms_clean_data($data){
	foreach($data as $index=>$value){
		if(!is_array($value)){
			$data[$index] = trim($value);
		}
	}

	return $data;
}
