<?php
// Defines the document type of requisiton document
// which is uploaded when an order is made
define ('DOC_REQUISION', 'REQUISITION');

/**
 * Saves a document in the database
 *
 * @param string $order_id the id of the order
 * @param string $location the current location of the file
 * @return void
 */
function document_save($order_id, $location) {
	static $count = 0;

	$document_id = sprintf('DOC_%d_%d', $order_id, $count);
	$query = "INSERT INTO document (DOCUMENT_ID, DOCUMENT_TYPE_ID, DATE_CREATED, COMMENTS, DOCUMENT_LOCATION, CREATED_STAMP, CREATED_TX_STAMP)
			  VALUES ('$document_id', '".DOC_REQUISION."', NOW(), 'Document for order $order_id', '".esc($location)."', '".now()."', NOW())";
	db_query($query);
	$count++;
}

/**
 * Uploads a file on the server
 *
 * @param string $order_id the id of the order
 * @param string $filename the required filename after upload
 * @param string $temp_filename the filename in the /tmp folder
 * @return boolean true copy was successfull false an error occured
 */
function document_upload($order_id, $filename, $temp_filename) {
	$filename = str_replace(DIRECTORY_SEPARATOR, '-', $filename);
	$new_file_location = sprintf('%s%s-%s', FILES_PATH, $order_id, $filename);
	$copied = move_uploaded_file($temp_filename, $new_file_location);

	if ($copied) {
		document_save($order_id, $new_file_location);
	}

	return $copied;
}

/**
 * Gets a list of documents by their associated order
 *
 * @param string $order_id the id of the order
 * @return array list with documents
 */
function documents_get($order_id) {
	$query = "SELECT * FROM document WHERE DOCUMENT_ID LIKE 'DOC_$order_id%'";
	return db_query_to_array($query);
}
