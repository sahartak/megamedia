<?php

/**
 * unset empty vars from $data_array array
 * @param array $data_array
 */
function clear_campaign_data(&$data_array, $count) {
	foreach($data_array as $key => &$data) {
		foreach($data as $store_id => &$store_items) {
			foreach($store_items as $store_key => &$store_item){
				$store_item = abs((int)$store_item);
				if(!$store_item){
					unset($store_items[$store_key]);
				}
			}
			if(count($store_items) < $count) {
				unset($data[$store_id]);
			}
		}
		if(empty($data)) {
			unset($data_array[$key]);
		}
	}
}

/**
 * insert $campaigns orders data to database
 * @param array $campaigns
 * @param int $order_id
 */
function insert_campaign_data(&$campaigns, $order_id, $type) {
	foreach($campaigns as $key => $campaign) {
		$campaign_id = insert_to_db('campaign_orders', array('order_id'=>$order_id, 'type' => $type));
		$insert_data = array();
		foreach($campaign as $store_id => $data) {
			$insert_data[] = array(
				'parent_id' => $campaign_id,
				'store_id' => abs((int)$store_id),
				'type_1' => isset($data['0']) ? $data['0'] : 0,
				'type_2' => isset($data['1']) ? $data['1'] : 0,
				'type_3' => isset($data['2']) ? $data['2'] : 0,
				'type_4' => isset($data['3']) ? $data['3'] : 0,
				'material_id' => isset($data['4']) ? $data['4'] : 0,
				'ophaeng_id' => isset($data['5']) ? $data['5'] : 0
			);
		}
		insert_multi_to_db('campaign_orders_items', $insert_data);
	}
}

/**
 * selecting campaign_orders data by $order_id
 * @param int $order_id
 * @return array $result
 */
function get_campaign_orders($order_id) {
	$campaign_orders = db_query_to_array('SELECT * FROM campaign_orders WHERE order_id = '.$order_id.' ORDER BY type');
	if($campaign_orders) {
		foreach($campaign_orders as &$campaign) {
			$campaign['materials'] = array();
			$campaign['items'] = array();
			$query = 'SELECT i.*, h.name as hanging_method FROM campaign_orders_items AS i
						LEFT JOIN hanging_methods as h ON h.id = i.ophaeng_id
						WHERE parent_id = '.$campaign['id'];
			$result = db_query_to_array($query);
			foreach($result as &$item) {
				$campaign['items'][$item['store_id']] = &$item;
				unset($item['store_id']);
			}
		}
		$result = array();
		foreach($campaign_orders as &$campaign) {
			$result[$campaign['type']][] = $campaign['items'];
		}
		return $result;
	}
	return false;
}

/**
 * selecting campaign_orders week_number by $order_id
 * @param int $order_id
 * @return array $week_numbers
 */
function get_campaign_orders_week_numbers($order_id) {
	$week_numbers = db_query_to_list('SELECT week_number FROM campaign_orders WHERE order_id = '.$order_id.' ORDER BY type', 'week_number');
	if($week_numbers) {
		return $week_numbers;
	}
	return array();
}

/**
 * selecting campaign_orders data as list
 * @param int $order_id
 * @return array $campaign_orders
 */
function get_campaign_orders_list($order_id) {
	$campaign_orders = db_query_to_list('SELECT id FROM campaign_orders WHERE order_id = '.$order_id.' ORDER BY type', 'id');
	return $campaign_orders;
}

/**
 * selecting campaign_orders stores
 * @param int $order_id
 * @return array $stores
 */
function get_campaign_stores($order_id) {
	$query = 'SELECT id FROM campaign_orders WHERE order_id = '.$order_id;
	$query = 'SELECT DISTINCT store_id FROM campaign_orders_items WHERE parent_id IN ('.$query.')';
	$query = 'SELECT name, id FROM stores WHERE id IN ('.$query.')';
	$stores = db_query_to_array($query);
	return $stores;
}

/**
 * counting sums for each type in campaign_orders
 * @param int $order_id
 * @return array $result
 */
function get_campaign_items_total($order_id) {
	$result = array();
	$campaign_orders = db_query_to_array('SELECT id FROM campaign_orders WHERE order_id = '.$order_id.' ORDER BY type');
	foreach($campaign_orders as $order){
		$query = 'SELECT SUM(type_1) as s_type_1, SUM(type_2) as s_type_2, SUM(type_3) as s_type_3, SUM(type_4) as s_type_4 FROM campaign_orders_items WHERE parent_id = '.$order['id'];
		$totals = db_query_to_array($query);
		$result[] = $totals[0];
	}
	return $result;
}

/**
 * getting campaign prices
 * @return array $prices
 */
function get_campaign_prices() {
	$prices = array(
		1 => 100, //banner
		2 => 100, //poster
		3 => 100 //rollup
	);
	return $prices;
}

/**
 * getting additional prices
 * @return array $prices
 */
function get_additional_prices() {
	$prices = array(
		1 => 100, //banner
		2 => 100, //poster
		3 => 100 //rollup
	);
	return $prices;
}

/**
 * counting price for item by square
 * @param int $width
 * @param int $height
 * @param int $square_price
 * @return int price
 */
function get_price_by_square($width, $height, $square_price) {
	return $width * 0.01 * $height * 0.01 * $square_price;
}

/**
 * getting additional orders by order type
 * @param int $order_id
 * @param int $$order_type
 * @return array $result
 */
function get_additional_orders_by_type($order_id, $type) {
	$query = "SELECT i.*, h.name AS hanging_method, s.name as store_name FROM additional_orders_items AS i
				LEFT JOIN hanging_methods AS h ON h.id = i.ophaeng_id
		INNER JOIN stores AS s ON s.id = i.store_id
				WHERE i.order_id = $order_id AND i.type = $type ORDER BY store_id";
	$result = db_query_to_array($query);
	return $result;
}

/**
 * getting user last campaign order information
 * @param int $user_id
 * @return array $campaign_orders or false
 */
function get_campaign_last_order($user_id) {
	$query = 'SELECT o.id FROM `orders` as o INNER JOIN campaign_orders as c ON c.order_id=o.id WHERE o.user_id = '.$user_id.' ORDER BY o.id DESC LIMIT 1';
	$order = db_query_to_list($query, 'id');
	if($order) {
		$campaign_orders = get_campaign_orders($order[0]);
		if($campaign_orders) {
			return $campaign_orders;
		}
	}
	return false;
}

/**
 * getting user last additional order details
 * @param int $user_id
 * @param int $type
 * @return array $additional_orders or false
 */
function get_last_additional_banner_order($user_id, $type) {
	$query = 'SELECT o.id FROM `orders` as o INNER JOIN additional_orders_items as a ON a.order_id=o.id WHERE o.user_id = '.$user_id.' AND a.type = '.$type.' ORDER BY o.id DESC LIMIT 1';
	$order = db_query_to_list($query, 'id');
	if($order) {
		$additional_orders = get_additional_orders_by_type($order[0], $type);
		if($additional_orders)
			return $additional_orders;
	}
	return false;
}

/**
 * getting campaign order total price
 * @param int $order_id
 * @param array & $campaign_orders
 * @return int $total_price
 */
function get_campaign_order_total_price($order_id, &$campaign_orders) {
	$campaign_totals = get_campaign_items_total($order_id);
	$prices = get_campaign_prices();
	$j = 0;
	$total_price = 0;
	foreach ($campaign_orders as $type => &$campaign) {
		$count = count($campaign);
		for ($i = 0; $i < $count; $i++) {

			if ($type == 1) {
				$price = round(
					$campaign_totals[$j]['s_type_1'] * get_price_by_square(390, 300, $prices[1]) +
					$campaign_totals[$j]['s_type_2'] * get_price_by_square(100, 100, $prices[1]) +
					$campaign_totals[$j]['s_type_3'] * get_price_by_square(300, 290, $prices[1]) +
					$campaign_totals[$j]['s_type_4'] * get_price_by_square(580, 450, $prices[1])
				);
			} else {
				$price = round(($campaign_totals[$j]['s_type_1'] + $campaign_totals[$j]['s_type_2'] + $campaign_totals[$j]['s_type_3'] + $campaign_totals[$j]['s_type_4']) * $prices[$type]);
			}
			$total_price += $price;
			$j++;
		}
	}
	return $total_price;
}

/**
 * getting additional (banner/order/roll up) order total price
 * @param array & $additional_orders
 * @param int $type ( 1(banner) / 2(poster) /3(roll up) )
 * @return int $total_price
 */
function get_additional_order_total_price(&$additional_orders, $type) {
	$add_prices = get_additional_prices();
	$total_price = 0;
	foreach($additional_orders as $item) {
		$total_price += ($item['amount'] * get_price_by_square($item['width'], $item['height'], $add_prices[$type]));
	}
	return round($total_price);
}

/**
 * getting array of all orders
 * @return array $orders
 */
function get_all_orders(&$pag_info) {
	$orders = array();
	$total = db_query_to_row("SELECT COUNT(id) as total FROM orders WHERE status > 0");
	if($total) {
		$count = $total['total'];
		$p = new Pagination();
		$page = isset($_GET['page']) ? abs((int)$_GET['page']) : 1;
		$pag_info = $p->calculate_pages($count, 10, $page);
		$orders = db_query_to_array("SELECT o.*, u.first_name, u.last_name FROM orders as o LEFT JOIN users as u ON u.id = o.user_id WHERE o.status > 0 ORDER BY o.id DESC ".$pag_info['limit']);
		if($orders) {
			foreach($orders as &$order) {
				$week_numbers = get_order_weeks($order['id']);
				$order['week_number'] = '';
				foreach($week_numbers as $week) {
					$order['week_number'] = $week;
					break;
				}
			}
		}
	}

	return $orders;
}

/**
 * getting order week numbers by order id
 * @return array $weeks
 */
function get_order_weeks($order_id) {
	$week_numbers = db_query_to_array("SELECT week_number, type FROM orders_weeks WHERE order_id = $order_id ORDER BY type");
	$weeks = array();
	foreach($week_numbers as $w) {
		$weeks[$w['type']] = $w['week_number'];
	}
	return $weeks;
}