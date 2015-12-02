<?php
import('pdf');
import('forms');

if (!empty($_POST['text'])) {

	// Defines used by the function
	define('PDF_DELIVERY_DATE_AMOUNT', 'DELIVERY_DATE_AMOUNT');
	define('PDF_DELIVERY_COUNTRY', 'DELIVERY_COUNTRY');
	define('PDF_DELIVERY_ADDRESS', 'DELIVERY_ADDRESS');
	define('PDF_DELIVERY_COMPANY', 'DELIVERY_COMPANY');
	define('PDF_DELIVERY_BEMERKNING', 'DELIVERY_BEMERKNING');

	function parse_product_data($Offer, $product, $current_page) {

		// Find Artikel number
		$status = 0;
		$number = '';
		foreach ($product as $_row) {
			switch ($status) {
				case 0:
					if ($_row == 'Artikel nr.') {
						$status = 1;
					}
				break;
				case 1:
					$number .= $_row . ' ';
					$status = 2;
				break;
				case 2:
					if ($_row == 'Artikel navn'){
						break 2;
					} else {
						$number .= $_row;
					}
				break;
			}
		}

		// Cleaning things we do not need
		$clean = [];
		foreach ($product as $_row) {
			if (
					stristr($_row, 'Sagsnavn:') ||
					stristr($_row, 'Sags nr.:') ||
					stristr($_row, 'Kunde rekvisitions nr.:') ||
					stristr($_row, '1. Opstart:') ||
					stristr($_row, 'Artikel nr.') ||
					stristr($_row, 'Artikel navn')
				) {
				continue;
			}
			$clean[] = $_row;
		}

		array_shift($clean);
		array_shift($clean);
		array_shift($clean);

		$Offer->verifyProductPage($number, $current_page);

		// Find Data Delivery and Delivery deadline
		// 2. Data levering: 14/08/2015 5. Leveringsdato: 01/10/2015
		// 3. RIP filer godkend: 14/08/2015 6. Distributionsdato: 01/09/2015
		$unset = [];
		foreach ($clean as $key => $value) {
			if (stristr($value, '2. Data levering:') && stristr($value, '5. Leveringsdato:')) {
				$dates = explode('5.', $value);
				$dates[0] = trim(str_replace('2. Data levering: ', '', $dates[0]));
				$dates[1] = trim(str_replace('Leveringsdato: ', '', $dates[1]));
				$Offer->getProduct($current_page)->setDate('file_delivery', $dates[0]);
				$Offer->getProduct($current_page)->setDate('print_deadine', $dates[1]);
				$unset[] = $key;
			} else if (stristr($value, '3. RIP filer godkend:')) {
				$dates = explode('6.', $value);
				$dates[0] = trim(str_replace('3. RIP filer godkend: ', '', $dates[0]));
				$Offer->getProduct($current_page)->setDate('file_approval', $dates[0]);
				$unset[] = $key;
			}
		}

		foreach ($unset as $value) {
			unset($clean[$value]);
		}

		$clean = array_values($clean);

		// Find metadata
		// Oplag: 500 Prod. Metode: Digitalprint
		// Format mm: B: 2500 H: 1200 VÃ¦gt pÃ¥ tryksag: gr.
		// Sider: 2 Antal skift: 1
		// Farver: 4+0 Lak: 0
		// Kvalitet: polyester FÃ¦rdiggÃ¸relse:
		// Materiale vÃ¦gt: 115 gr.
		// Fil navn:

		$unset = [];
		foreach ($clean as $index => $value) {
			if (stristr($value, 'Oplag:') && stristr($value, 'Prod. Metode:')) {
				$unset[] = $index;
				$arr = explode('Prod. Metode:', $value);
				$arr[0] = str_replace('Oplag:', '', $arr[0]);
				$Offer->getProduct($current_page)->set('circulation', $arr[0]);
				$Offer->getProduct($current_page)->set('method', $arr[1]);

			} else if (stristr($value, 'Format mm:') && stristr($value, 'Vægt på tryksag:')) {
				$unset[] = $index;
				$arr = explode('Vægt på tryksag:', $value);
				$arr[0] = trim(str_replace(['Format mm:', 'B: ', 'H: '], '', $arr[0]));
				$dimensions = explode(' ', $arr[0]);
				$Offer->getProduct($current_page)->set('format_width', $dimensions[0]);
				$Offer->getProduct($current_page)->set('format_height', $dimensions[1]);
				$Offer->getProduct($current_page)->set('printing_weight', $arr[1]);

			} else if (stristr($value, 'Sider:') && stristr($value, 'Antal skift:')) {
				$unset[] = $index;
				$arr = explode('Antal skift:', $value);
				$arr[0] = str_replace('Sider:', '', $arr[0]);
				$Offer->getProduct($current_page)->set('sides', $arr[0]);
				$Offer->getProduct($current_page)->set('shifts', $arr[1]);

			} else if (stristr($value, 'Farver:') && stristr($value, 'Lak:')) {
				$unset[] = $index;
				$arr = explode('Lak:', $value);
				$arr[0] = str_replace('Farver:', '', $arr[0]);
				$Offer->getProduct($current_page)->set('colours', $arr[0]);
				$Offer->getProduct($current_page)->set('polish', $arr[1]);

			} else if (stristr($value, 'Kvalitet:') && stristr($value, 'Færdiggørelse:')) {
				$unset[] = $index;
				$arr = explode('Færdiggørelse:', $value);
				$arr[0] = str_replace('Kvalitet:', '', $arr[0]);
				$Offer->getProduct($current_page)->set('quality', $arr[0]);
				$Offer->getProduct($current_page)->set('completion', $arr[1]);

			} else if (stristr($value, 'Materiale vægt:') ) {
				$unset[] = $index;
				$Offer->getProduct($current_page)->set('material_weight', str_replace('Materiale vægt:', '', $value));

			} else if (stristr($value, 'Fil navn:') ) {
				$unset[] = $index;
				$file_name = $index;

			}
		}

		$notes = '';
		$count = count($clean);
		for ($i = $file_name+1; $i < $count; ++$i) {
			$notes .= ' ' . $clean[$i];
			$unset[] = $i;
		}

		$Offer->getProduct($current_page)->set('notes', trim($notes));
		foreach ($unset as $value) {
			unset($clean[$value]);
		}

		$clean = array_values($clean);

		// Bemerknign
		// Delivery information
		if ($clean[count($clean)-1] == 'Leverings info: Oplag Dato Pakke instruks' ) {
			array_pop($clean);
			$count = count($clean);
			$i = $count - 1;

			// Here it could be either the amount + date
			// or the country (country could be missing)
			// or the address or the company to which it needs
			// to be delivered + Att.: to whom is going to accept it

			$found_address = false;
			$delivery_parse_status = PDF_DELIVERY_DATE_AMOUNT;
			$lines = [];

			while($i > -1) {
				$lines[] = $clean[$i];

				if (stristr($clean[$i], 'Att.:')) {
					$Offer->getProduct($current_page)->addDeliveryInfo($lines);
					$lines = [];
				}

				if (stristr($clean[$i], 'Bemærkning:')) {
					$Offer->getProduct($current_page)->addComment(implode("\n", $lines));
					$lines = [];
				}

				$i--;
			}

		} else {
			echo ('Could not parse address on page:' . $current_page);
		}

		return;
	} // End of Parse product data

	// Statuses
	define('BEGINING', 'BEGINING');
	define('MEGAMEDIA_ADDRESS', 'MEGAMEDIA_ADDRESS');
	define('COMPLETED', 'COMPLETED');
	define('BEGINING_PRODUCT_PAGE', 'BEGINING_PRODUCT_PAGE');
	define('BEGINING_CONTENTS_PAGE', 'BEGINING_CONTENTS_PAGE');
	define('END_CONTENTS_PAGE', 'END_CONTENTS_PAGE');
	define('END_PRODUCT_PAGE', 'END_PRODUCT_PAGE');
	define('CONTENTS', 'CONTENTS');

	//Keywords
	define('MEGAMEDIA_ADDRESS_FINAL', 'Patrick Frey');

	$page_heading = 'Forespørgsel';
	$last_row_before_contents = 'Kunde rekvisitions nr.:';
	$contents_end = 'Med venlig hilsen';
	$subject_start = 'Sagsnavn';

	$total_pages = 0;
	$current_page = 1;
	$status = BEGINING;
	$Offer = new Offer();
	$rows = explode("\n", $_POST['text']);
	$debug = $rows;

	$header_data = [
		'Media|Print A/S',
		'Skt. Petri Passage 5, 2nd floor',
		'1165 Copenhagen - Denmark',
		'+45 8230 4200',
		'Bødker Balles Gård 15',
		'8000 Aarhus, Denmark',
		'+45 8230 4200',
		'CVR: DK28865554'
	];



	if (!stristr($rows[0], $page_heading)) {
		$rows[0] = $page_heading . ' ' . $rows[0];
	}

	try {
		foreach ($rows as $index => $_row) {
			$_row = trim($_row);

			if (in_array($_row, $header_data)) {
				continue;
			}

			switch ($status) {
				case BEGINING:
					// Checking if beginning of page
					if (stristr($_row, $page_heading)) {
						$numberArray = explode(' ', $_row);
						$Offer->setEnquiryNumber($numberArray[1]);
						$status = MEGAMEDIA_ADDRESS;
					}
				break;

				case MEGAMEDIA_ADDRESS:
					// We do not need to handle the megamedia address
					// because we know it already.
					// @todo Add checks if too many rows have been parsed
					if ($_row == MEGAMEDIA_ADDRESS_FINAL) {
						if ($current_page == 1) {
							$status = BEGINING_CONTENTS_PAGE;
						} else {
							$status = BEGINING_PRODUCT_PAGE;
						}
					}
				break;

				case BEGINING_CONTENTS_PAGE:
					// Check if the contents page has ended and
					// verify the order number just in case
					if (mb_stristr($_row, $page_heading)) {
						$numberArray = explode(' ', $_row);
						$Offer->verifyEnquiryNumber($numberArray[1]);
					} else if (stristr($_row, $last_row_before_contents) || $_row == $last_row_before_contents) {
						$status = CONTENTS;
					} else if (stristr($_row, $subject_start)) {
						$Offer->setSubject(str_replace('Sagsnavn:', '', $_row));
					}

				break;

				case CONTENTS:
					// Check if the products have ended
					// otherwise parse the prodct data
					if ($_row == $contents_end) {
						$current_page++;
						$status = MEGAMEDIA_ADDRESS;
						$Offer->setMaxPages((int)$pageArr[0]);
					} else {
						if (!stristr($_row, ' Bilag s. ')) {
							continue;
						}

						$product = explode(' Bilag s. ', $_row);

						if (sizeof($product) != 2) {
							throw new RuntimeException(sprintf('Could not parse product "%s"', $_row));
						}

						$Product = new OfferProduct();
						$Product->setNumber($product[0]);

						$pageArr = explode(' ', $product[1]);
						$Product->setPage((int)$pageArr[0]);
						$Product->setName($pageArr);
						$Offer->addProduct($Product, $pageArr[0]);
					}
				break;

				case BEGINING_PRODUCT_PAGE:
					// If we have reached the end of the page the
					// address of megamedia has come and a new page has
					// begun so we increment and switch that status
					if ($_row == sprintf('%s/%s', $current_page, $Offer->max_pages)) {
						parse_product_data($Offer, $current_product, $current_page);
						$current_page++;
						$current_product = [];
						$status = MEGAMEDIA_ADDRESS;
					} else {
						$current_product[] = $_row;
					}

				break;
			}

			array_shift($debug);
		} // End of foreach

		$Offer->validateMotives();
		$Offer->validateDeliveryDates();
		$Offer->validateAmount();
		$Offer->validateQuality();
	} catch (Exception $e) {
		$errors[] = $e->getMessage();

	}
}

$template = set_template('parse', 'parse');
$link = THEME . 'template.php';
require_once($link);
