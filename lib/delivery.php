<?php

$system_settings = get_settings();

define('DELIVERY_PALLET', 50);
define('DELIVERY_PALLET_PRICE', 1600.00);
define('DELIVERY_TUBE_PRICE', 50.00);
define('DELIVERY_ENVIRONMENT', $system_settings['ENVIRONMENT_COST']);

function delivery_calculate($runs) {
	return $runs > DELIVERY_PALLET ? DELIVERY_PALLET_PRICE : DELIVERY_TUBE_PRICE;
}
