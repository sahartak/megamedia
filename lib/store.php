<?php

/**
 * Validate post data for store
 * @param empty array $data
 * @return bool
 */
function store_validate(&$data) {
	if(isset($_POST['shop_name']) && !empty($_POST['shop_name']))
		$data['name'] = htmlspecialchars(strip_tags(trim($_POST['shop_name'])));

	if(isset($_POST['street_name']) && !empty($_POST['street_name']))
		$data['street_name'] = htmlspecialchars(strip_tags(trim($_POST['street_name'])));

	if(isset($_POST['city']) && !empty($_POST['city']))
		$data['city'] = htmlspecialchars(strip_tags(trim($_POST['city'])));

	if(isset($_POST['postal_code']) && !empty($_POST['postal_code']))
		$data['postal_code'] = (int)trim($_POST['postal_code']);

	if(isset($_POST['country']) && !empty($_POST['country']))
		$data['country'] = htmlspecialchars(strip_tags(trim($_POST['country'])));

	if(isset($_POST['contact_name']) && !empty($_POST['contact_name']))
		$data['contact_name'] = htmlspecialchars(strip_tags(trim($_POST['contact_name'])));

	if(isset($_POST['phone_number']) && !empty($_POST['phone_number']))
		$data['phone_number'] = htmlspecialchars(strip_tags(trim($_POST['phone_number'])));

	if(isset($_POST['email_address']) && !empty($_POST['email_address']))
		$data['email_address'] = htmlspecialchars(strip_tags(trim($_POST['email_address'])));

	if(isset($_POST['hanging_methods']) && !empty($_POST['hanging_methods'])) {
		foreach($_POST['hanging_methods'] as $method) {
			$method = abs((int)$method);
			if($method)
				$data['hanging_methods'][] = $method;
		}
	}

	if(isset($_POST['lat']) && !empty($_POST['lat']))
		$data['lat'] = htmlspecialchars(strip_tags(trim($_POST['lat'])));

	if(isset($_POST['long']) && !empty($_POST['long']))
		$data['long'] = htmlspecialchars(strip_tags(trim($_POST['long'])));

	if(count($data) < 11) {
		return false;
	}
	return true;
}

/**
 * getting countries array
 * @return array
 */
function get_countries() {
	return array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina",
		"Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin",
		"Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory",
		"Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands",
		"Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo,
		 the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark",
		"Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia",
		"Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia",
		"French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe",
		"Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras",
		"Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan",
		"Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan",
		"Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania",
		"Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta",
		"Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of",
		"Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles",
		"New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan",
		"Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion",
		"Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino",
		"Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia",
		"Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena",
		"St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic",
		"Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia",
		"Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States",
		"United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)",
		"Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");
}

/**
 * getting all hanging methods array from database
 * @return array
 */
function get_hanging_methods() {
	$result = db_query_to_array("SELECT * FROM `hanging_methods`");
	return $result;
}

/**
 * getting store hanging methods array from databas
 * @param int $store_id
 * @return array
 */
function get_store_hanging_methods($store_id) {
	$store_id = abs((int)$store_id);
	$query = "SELECT h.* FROM stores_hanging_methods as s INNER JOIN hanging_methods AS h ON h.id = s.hanging_method_id WHERE s.store_id = $store_id";
	$result = db_query_to_array($query);
	return $result;
}

/**
 * getting materials array
 * @return array
 */
function get_materials() {
	$materials = array(
		1 => 'PVC Frontlight',
		2 => 'PVC Mesh',
		3 => 'Blueback',
		4 => 'Citylight'
	);
	return $materials;
}