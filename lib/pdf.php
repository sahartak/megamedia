<?php

/**
 * Offer class
 */
class Offer {
	public $enquery_number;

	public $products;

	public $max_pages;

	public $enquery_subject;

	public function setSubject($string) {
		$string = trim($string);

		if (empty($string)) {
			throw new BadMethodCallException('Inquiry subject cannot be empty');
		}

		$this->enquery_subject = $string;
	}

	public function setEnquiryNumber($number) {
		$number = (int)trim($number);

		if (empty($number)) {
			throw new RuntimeException('Count not recognize enquiry number!');
		}

		$this->enquery_number = $number;
	}

	public function verifyEnquiryNumber($number) {
		if (empty($number)) {
			throw new RuntimeException('Count not recognize enquiry number!');
		}

		if ($number != $this->enquery_number) {
			throw new RuntimeException('Enquiry number mismatch probably contains more than one enquiry!');
		}
	}

	public function addProduct($Product, $page) {
		if (!empty($this->products[$page])) {
			throw new BadMethodCallException(sprintf('Trying to add already existing product fot page %s', $page));
		}

		$this->products[$page] = $Product;
	}

	public function verifyProductPage($name, $page) {
		$name = trim($name);

		if ($page > $this->max_pages) {
			throw new RuntimeException('Exeded the number of pages in the contents');
		}

		if ($name != $this->products[$page]->getName()) {
			throw new RuntimeException(sprintf('Product missmatch on page %d ("%s" VS "%s")', $page, $name, $this->products[$page]->getName()));
		}

		return true;
	}

	public function setMaxPages($page) {
		if (empty($page)) {
			throw new BadMethodCallException('Max page cannot be empty');
		}

		if ($page < 1) {
			throw new BadMethodCallException('Max page cannot be less than 2');
		}

		$this->max_pages = $page;
	}

	public function getProduct($page) {
		if ($page < 2 || $page > $this->max_pages) {
			throw new BadMethodCallException('Product page must be in the allowed limit');
		}

		return $this->products[$page];
	}

	public function getProducts() {
		return $this->products;
	}

	public function validateMotives() {
		foreach ($this->products as $page => $_product) {
			$arr = explode('_', $_product->getNumber());
			// var_dump($_product->getShifts());
			// If there is no number in the end we skip this check
			$last = array_pop($arr);
			if (!is_numeric($last)) {
				continue;
			}

			// Otherwise we make sure that there is a difference of 1 in the shifts
			$_product->getShifts();
		}
	}

	public function validateDeliveryDates() {
		foreach ($this->products as $page => $_product) {

			foreach ($_product->getDeliveryInfo() as $Delivery) {
				if (str_replace('/', '.', $Delivery->getDate()) != $_product->getDeliveryDate()) {
					// throw new Exception(sprintf("Mismatch in delivery dates on page %d: (%s vs %s)", $page, $Delivery->getDate(), $_product->getDeliveryDate()));
				}
			}

		}
	}

	public function validateAmount() {
		foreach ($this->products as $page => $_product) {
			$totalAmount = 0;
			$mediaprintCount = 0;

			foreach ($_product->getDeliveryInfo() as $Delivery) {
				$totalAmount += $Delivery->getAmount();

				if (stristr($Delivery->getAddress(), 'Media-Print')) {
					$mediaprintCount++;
				}
			}

			if ($totalAmount != $_product->getCirculation() && $totalAmount != $_product->getCirculation() + $mediaprintCount) {
				throw new Exception(sprintf("Mismatch in circulations on page %d: (%s vs %s)", $page, $_product->getCirculation(), $totalAmount));
			}
		}
	}

	public function validateQuality() {
		foreach ($this->products as $page => $_product) {
			$quality = $_product->getQuality();
			if (empty($quality)) {
				throw new RuntimeException('Empty quality on page ' . $page);
			}
		}
	}
}

/**
 * Offer product class
 */
class OfferProduct {
	public $name;
	public $page;
	public $number;

	public $file_delivery;
	public $file_approval;
	public $print_deadine;

	public $circulation;
	public $method;
	public $format_width;
	public $format_height;
	public $printing_weight;
	public $sides;
	public $shifts;
	public $colours;
	public $polish;
	public $quality;
	public $completion;
	public $material_weight;
	public $notes;

	public $Deliveries;

	public function set($var, $value) {
		$this->{$var} = trim($value);
	}

	public function setName($name) {
		array_shift($name);
		$name = implode(' ', $name);

		if (empty($name)) {
			throw new BadMethodCallException('Product name cannot be empty');
		}

		$this->name = trim($name);
	}

	public function setPage($page) {
		if (empty($page)) {
			throw new BadMethodCallException('Product page cannot be empty');
		}

		if ($page < 2) {
			throw new BadMethodCallException('Product page cannot be less than 2');
		}

		$this->page = $page;
	}

	public function setNumber($number) {
		if (empty($number)) {
			throw new BadMethodCallException('Product number cannot be empty');
		}

		$this->number = trim($number);
	}

	public function getQuality() {
		return $this->quality;
	}

	public function getCirculation() {
		return $this->circulation;
	}

	public function getNumber() {
		return $this->number;
	}

	public function getName() {
		return $this->name;
	}

	public function getShifts() {
		return $this->shifts;
	}

	public function getDeliveryDate() {
		return $this->print_deadine;
	}

	public function setDate($date, $value) {
		if (empty($value)) {
 			return;
		}

		$delimiter = '/';
		if (stristr($value, '.')) {
			$delimiter = '.';
		}

		$arr = explode($delimiter, $value);
		$mysql_date = sprintf('%d-%d-%d', $arr[2], $arr[1], $arr[0]);

		if (!is_valid_date($mysql_date)) {
			throw new RuntimeException('Invalid date');
		}

		if (strtotime($mysql_date) <= time($mysql_date)) {
			// throw new RuntimeException('Date must be into the future');
		}

		$this->{$date} = $value;
	}

	public function addDeliveryInfo($lines) {
		// Getting pieces and date
		$arr = explode(' ', $lines[0]);

		$Delivery = new Delivery;
		$Delivery->addPiece($arr[0]);
		$Delivery->addDate($arr[2]);

		array_shift($lines);
		$Delivery->addAddress(implode("\n", $lines));
		$this->Deliveries[] = $Delivery;
	}

	public function getDeliveryInfo() {
		return $this->Deliveries;
	}

	public function addComment($comment) {
		$this->notes = trim(str_replace('Bemærkning:', '', $comment));
	}
}

/**
 * Delivery class
 */
class Delivery {
	public $piece;
	public $date;
	public $address;

	public function addPiece($pieces) {
		if ((int)$pieces <= 0) {
			throw new RuntimeException("Pieces in delivery must be a positive number");
		}

		$this->piece = $pieces;
	}

	public function addDate($date) {
		if (!is_valid_date($date)) {
			throw new RuntimeException('Invalid date for delivery');
		}

		$this->date = $date;
	}

	public function addAddress($address) {
		$this->address = $address;
	}

	public function getDate() {
		return $this->date;
	}

	public function getAmount() {
		return $this->piece;
	}

	public function getAddress() {
		return $this->address;
	}
}
