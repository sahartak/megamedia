<?php

/**
 *
 * Connects to the database
 */
function db_connect(){
	if($_SERVER['HTTP_HOST'] == 'mp.megamedia.dev'){
		mysql_connect('localhost', 'root', '');
		mysql_select_db('megamedia_b2b');
	} else if($_SERVER['HTTP_HOST'] == 'mp.petev.net') {
		mysql_connect('localhost', 'petevnet_megamed', 'IfJp{R2Kgv%5');
		mysql_select_db('petevnet_megamedia');
	}
	else{
		@mysql_connect('localhost', 'root', '');
		mysql_select_db('haraldnyborg');
	}

	mysql_query("SET NAMES utf8");
}

/**
 *
 * Escapes a string for database operation with it
 * @param str $unescaped_string - unescaped string
 * @return str - escaped string
 */
function esc($unescaped_string){
	return mysql_real_escape_string($unescaped_string);
}

/**
 *
 * Executes a query and return an array witn one row
 * @param str $query - MySQL query
 * @return arr - result
 */
function db_query_to_row($query){
	$return = array();

	$result = db_query($query);
	if( is_resource($result) && mysql_num_rows($result) > 0 ){
		$return = mysql_fetch_assoc($result);
	}

	foreach($return as $key=>&$value){
		$value = stripslashes(stripslashes($value));
	}

	return $return;
}

/**
 *
 * Executes a query to the database
 * @param str $query - MySQL query
 */
function db_query($query){
	$result = mysql_query($query) or die(mysql_error());
	if( mysql_errno() ){

		$fptr = fopen('error_log.txt', 'a+');
		fwrite($fptr, date('Y-m-d H:i:s').'|||'.$_SERVER['REQUEST_URI'].'|||'.mysql_error().'|||'.$query."\n");
		fclose($fptr);
	}
	return $result;
}

/**
 *
 * Executes a query and return an array witn many rows with the result
 * @param str $query - MySQL query
 * @return arr - result
 */
function db_query_to_array($query){
	$return = array();

	$result = db_query($query);
	if( is_resource($result) && mysql_num_rows($result) > 0 ){
		while($row = mysql_fetch_assoc($result)){
			$return[] = $row;
		}
	}

	foreach($return as $key=>&$row){
		foreach($row as $column=>&$value){
			$value = stripslashes(stripslashes($value));
		}
	}

	return $return;
}

/**
 * Returns a list with values from the result
 * of the specified query
 *
 * @param string $query the MySQL query
 * @param string $column the column from the result
 * @return array list with values
 */
function db_query_to_list($query, $column) {
	$return = array();
	$data = db_query_to_array($query);

	if (!empty($data)) {
		foreach ($data as $row) {
			if (isset($row[$column])) {
				$return[] = $row[$column];
			}
		}
	}

	return $return;
}

/**
 *
 * Calculate the limit clause of query based on the page number
 * @param int $page - number of page
 * @return str - the limit rule
 */
function db_get_limit($page){

	$page = (int)$page;
	$start = $page == 0 || $page == 1?0:($page-1) * ROWS_PER_PAGE;

	return " LIMIT $start,".ROWS_PER_PAGE;
}

/**
 *
 * insert $data to $table in database
 * @param string $table - table name
 * @param array $data - insert data
 * @return int - insert id
 */
function insert_to_db($table, $data) {
	if($table && sizeof($data)) {
		$fields = '';
		$values = '';
		foreach($data as $key => $value) {
			$key = esc($key);
			$fields .= '`'.$key.'`,';
			$value = esc($value);
			$values .= "'$value',";
		}
		if($fields && $values) {
			$fields = substr($fields, 0, -1);
			$values = substr($values, 0, -1);
		}
		$query = 'INSERT INTO '.$table.' ('.$fields.') VALUES ('.$values.')';
		db_query($query);
		return mysql_insert_id();
	}
	return false;
}
/**
 *
 * insert multiple data to $table in database
 * @param string $table - table name
 * @param array $multi_data - multiple array each item insert data
 * @return bool
 */
function insert_multi_to_db($table, $multi_data) {
	if($table && sizeof($multi_data) && sizeof($multi_data[0])) {
		$fields = '';

		foreach($multi_data[0] as $key => $value) {
			$key = esc($key);
			$fields .= '`'.$key.'`,';
		}
		$fields = substr($fields, 0, -1);
		$all_values = '';

		foreach($multi_data as $data) {
			$values = '(';
			foreach($data as $key => $value) {
				$value = esc($value);
				$values .= "'$value',";
			}
			$values = substr($values, 0, -1).'),';
			$all_values .= $values;
		}
		$all_values = substr($all_values, 0, -1);
		$query = 'INSERT INTO '.$table.' ('.$fields.') VALUES '.$all_values;
		db_query($query);
		return true;
	}
	return false;
}

/**
 *
 * update $data to $table in database
 * @param string $table - table name
 * @param array $data - update data
 * @param string $where - conditions
 * @return bool
 */
function update_in_db($table, $data, $where='') {
	if($table && sizeof($data)) {
		$fields = '';
		foreach($data as $key => $value) {
			$key = esc($key);
			$value = esc($value);
			$fields .= "`$key` = '$value',";
		}
		if($fields) {
			$fields = substr($fields, 0, -1);
		}
		if($where){
			$where = 'WHERE '.$where;
		}
		$query = "UPDATE $table SET $fields $where";
		db_query($query);
		return true;
	}
	return false;
}

/**
 *
 * delete data from $table in database
 * @param string $table - table name
 * @param string $where - conditions
 */
function delete_from_db($table, $where = '') {
	if($where){
		$where = 'WHERE '.$where;
	}
	$query = "DELETE FROM `$table` $where";
	db_query($query);
}