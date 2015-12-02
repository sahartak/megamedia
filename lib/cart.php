<?php
/**
 * This is the Cart class that will take care for the
 * cart functionality
 */
class Cart {

/**
 * The place where the cart data is store
 *
 * @var array
 */
	private $cart;

/**
 * If the cart comes from an offer
 * then we store the id of the offer
 *
 * @var string
 */
	private $from_offer_id;

/**
 * Cosntructor
 *
 * @return void
 */
	public function __construct() {
		if (isset($_SESSION['CART'])) {
			$this->cart = $_SESSION['CART'];
			$this->setFromOffer($_SESSION['CART']['FROM_OFFER']);
		} else {
			$this->cart = array();
			$this->cart['PRODUCTS'] = array();
			$this->setFromOffer('');
		}
	}

/**
 * Destructor
 *
 * @return void
 */
	public function __destruct() {
		$this->_updateSession();
	}

/**
 * Adds and element to the cart
 * The element must contain a PRICE key
 *
 * @throws BadMethodCallException If there is no PRICE element present
 * @param array $elem the new element to be added
 */
	public function add($elem) {
		if (!isset($elem['PRICE'])) {
			throw new BadMethodCallException('The new cart element must have PRICE set');
		}

		$this->cart['PRODUCTS'][] = $elem;
		$this->_updateTotal();

		return count($this->cart['PRODUCTS']) - 1;
	}

/**
 * Updates the grant total of the cart
 *
 * @return void
 */
	protected function _updateTotal() {
		$this->cart['GRAND_TOTAL'] = 0.00;

		if (!empty($this->cart['PRODUCTS'])) {
			foreach ($this->cart['PRODUCTS'] as $_product) {
				$this->cart['GRAND_TOTAL'] += $_product['PRICE'];
			}
		}
	}

/**
 * Updates the cart info in the session
 *
 * @return void
 */
	protected function _updateSession() {
		$_SESSION['CART'] = $this->cart;
	}

/**
 * Removes a product from the cart
 *
 * @param integer $index the index where the element is
 * @return boolean | array - false if there is no element on that
 * index or array with the product data
 */
	public function remove($index) {
		$return = false;

		if (isset($this->cart['PRODUCTS'][$index])) {
			$return = $this->cart['PRODUCTS'][$index];
		}

		unset($this->cart['PRODUCTS'][$index]);
		$this->_updateTotal();
		$this->cart['PRODUCTS'] = array_values($this->cart['PRODUCTS']);

		return $return;
	}

/**
 * Gets the list of all products
 *
 * @return array list with products
 */
	public function getProducts() {
		return $this->cart['PRODUCTS'];
	}

/**
 * Gets the count of the products in the cart
 *
 * @return integer the number of products
 */
	public function getProductsCount() {
		return count($this->getProducts());
	}

/**
 * Gets the total price of the products in
 * the cart
 *
 * @return float the total price
 */
	public function getTotal() {
		return $this->cart['GRAND_TOTAL'];
	}

/**
 * Resets the cart to an empty state
 *
 * @return void
 */
	public function reset() {
		$this->cart['PRODUCTS'] = array();
		$this->cart['ADDRESS'] = array();
		$this->cart['GRAND_TOTAL'] = 0.00;
		$this->cart['FROM_OFFER'] = NULL;
		$this->_updateSession();
	}

/**
 * Gets a product from an index in the cart
 *
 * @param integer $index index in the cart
 * @return array the product data that was stored in the cart
 */
	public function getAt($index) {
		$products = $this->getProducts();
		return isset($products[$index]) ? $products[$index] : false;
	}

/**
 * Returns the serialized cart content when
 * the object is used as a string
 *
 * @return string
 */
	public function __toString() {
		return serialize($this->cart);
	}

/**
 * Builds the content of the Cart from
 * a previously serialized cart content
 *
 * @param string $string the serialized cart content
 * @return void
 */
	public function fromString($string) {
		$this->cart = unserialize($string);
	}

/**
 * When the user has started from an existing offer
 * to be used for overriding the existing offer on save
 * or an order to be placed on top of the offer
 *
 * @param string $offer_id the id of the offer
 */
	public function setFromOffer($offer_id) {
		$this->from_offer_id = $offer_id;
		$this->cart['FROM_OFFER'] = $offer_id;
	}

/**
 * Checks if the current cart comes from an
 * existing in the database offer
 *
 * @return boolean true it does, false it does not
 */
	public function isFromOffer() {
		return $this->from_offer_id != NULL;
	}

/**
 * Gets the id of the offer from which the
 * cart came from
 *
 * @return string - the id of the offer
 */
	public function getOfferId() {
		return $this->from_offer_id;
	}

/**
 * Adds delivery addresses to the products ordered
 * based on their index
 *
 * @param integer $index the index in the products array where
 * the products are stored inside the cart in the session
 * @param array $address list with pieces with addresses
 * the lenght of the array is the number of addresses and
 * the values are the pieces to be delivered there.
 */
	public function addAddresses($index, $address) {
		$this->cart['ADDRESS'][$index] = $address;
		$this->_updateSession();
	}

/**
 * Gets the delivery distribution for a single product that has been ordered
 *
 * @param integer $index the index at which the product lies
 * @return array list with distributions
 */
	public function deliveryDistributionAt($index) {
		return $this->cart['ADDRESS'][$index];
	}

}
