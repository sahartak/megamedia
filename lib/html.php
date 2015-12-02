<?php
import('shipments');

/**
 * Calculates the data needed for the offer boxes
 *
 * @param array $request the request from the POST or the SESSIOn
 * @param array $locations list with locations with the units being delivered
 * @return array values to be used later on
 */
function html_offer_box($request, $locations) {
	// Calculating prices
	$headers = product_feature_all(true);
	$rows = array();
	$product_prices = array();
	$variants = isset($request['VARIANT']) ? array_unique($request['VARIANT']) : array();
	$copies = array();
	$estimated_delivery = array();
	$estimated_production = array();
	$runs = array();

	// Getting the prices for each variant
	foreach ($variants as $index => $_product) {
		$product_prices[$_product] = product_get_all_prices($_product, $_SESSION['user']['PARTY_ID'], true);
		$copies[$_product] = product_assoc_get($_product, PRODUCT_ASSOC_PAIR, true);
	}

	// Getting the price of the pair products in this case the
	// copies it will be most probably be always the same case
	// where the copies are the procuts
	foreach ($copies as $_main_product => &$_pair_product) {
		$_pair_product[$_pair_product[0]['PRODUCT_ID']] = product_get_all_prices($_pair_product[0]['PRODUCT_ID'], $_SESSION['user']['PARTY_ID'], true);
	}

	$delivery = 0.00;
	$units = 0;
	if (!empty($request['MOTIVES'])) {
		foreach ($request['MOTIVES'] as $index => $_motive) {
			if ($_motive) {
				$new_row = array();
				foreach ($headers as $_header) {
					$new_row[$_header] = $_motive * product_price_by_header($request['VARIANT'][$index], $product_prices[$request['VARIANT'][$index]]['FEATURES'], $_header);
					$copy_price = product_price_by_header(
						$copies[$request['VARIANT'][$index]][0]['PRODUCT_ID'],
						$copies[$request['VARIANT'][$index]][$copies[$request['VARIANT'][$index]][0]['PRODUCT_ID']]['FEATURES'],
						$_header);
					$new_row[$_header] += ($request['RUNS'][$index] * $copy_price);
					$units += $request['RUNS'][$index];
				}

				// The mixed index prices is the lowest price of all production lines
				// so the customer can have some super nice and low price but first we
				// remove all prices that are 0-s
				foreach ($new_row as $_new_row_header => $_new_row) {
					if (!$_new_row) {
						unset($new_row[$_new_row_header]);
					}
				}

				$new_row['MIXED'] = min($new_row);
				$rows[$index] = $new_row;
			}
		}
	}

	foreach ($locations as $_units) {
		$delivery += shipment_cost_estimate($_units);
	}

	// Adding the MIXED header to all calculations
	array_push($headers, 'MIXED');

	return array($headers, $rows, $variants, $copies, $delivery);
}

/**
 * Builds the actual offer box with all the
 * data populated in it
 *
 * @param array $rows with data about the box
 * @param array $variants the variants of the products
 * @param array $copies the copies variants of the products
 * @param array $delivery the delivery price
 * @param array $_header to be the label like DIGITAL / OFFSET
 * @param array $motives the motives of the product
 * @param array $display whether to hide or display some data
 * @param boolean $allow_actions whether we allow actions to happen
 * @return string the HTML code to build the box
 */
function html_build_offer_box($rows, $variants, $copies, $delivery, $_header, $motives, $display, $allow_actions, $runs, $selected_variants) {
	static $box_idnex = 0;

	// We need to check if there is any need to display that row at all
	$check = 0;
	if (!empty($motives)) {
		foreach ($motives as $index => $_motive) {
			if (!$_motive) {
				continue;
			}

			if (!isset($rows[$index][$_header])) {
				continue;
			}

			$check += $rows[$index][$_header];
		}
	}

	if (!$check) {
		return '';
	}

	$return = '
		<li class="span4">
			<div class="thumbnail">
				<div class="caption">
					<h3 style="margin-left: 0px;">';

					// If we are in the cart then we display the delete button
					if ($allow_actions && !$display) {
						$return .= '<a data-type="confirm" data-toggle="tooltip" title="Delete" style="margin-top: -5px;" href="/orders/cart?DELETE=' . $box_idnex . '" class="btn btn-mini btn-danger"><i class="icon-trash icon-white"></i></a>
									<a data-toggle="tooltip" title="Edit" style="margin-top: -5px;margin-right: 10px;" href="/orders/cart?CHANGE=' . $box_idnex . '" class="btn btn-mini btn-warning"><i class="icon-pencil icon-white"></i></a>';
					}

			$return .= $_header . ' <small>(' . substr($variants[1], 0, stripos($variants[1], '-')) . ')</small>';
					if ($allow_actions && !$display) {
						$return .= '<input type="file" name="requisition[' . $box_idnex . ']" />';
					}
			$return .= '</h3>
					<p style="text-align: center;">
						<div class="row">
							<table style="font-size: 15px; margin-left: 30px; width: 340px;">
								<tr>
									<th style="background-color: #fff;"><b>Line</b></th>
									<th style="background-color: #fff;"><b>Motives</b></th>
									<th style="background-color: #fff;"><b>Runs</b></th>
									<th style="background-color: #fff;"><b>Variant</b></th>
									<th style="background-color: #fff;"><b>Price</b></th>
								</tr>
							';

							$total = 0;

							foreach ($motives as $index => $_motive) {
								// If we do not have a motive for that line then
								// we simply do not display it
								if (!$_motive) {
									continue;
								}
								$total += $rows[$index][$_header];

					$return .= '<tr>
									<td class="span1">' . $index . '</td>
									<td class="span1">' . $motives[$index] . '</td>
									<td class="span1">' . $runs[$index] . '</td>
									<td class="span1">' . substr($selected_variants[$index], stripos($selected_variants[$index], '-') + 1) .'</td>
									<td class="span2" style="text-align: right;">
										<strong style="margin-left:25px;">' . number_format($rows[$index][$_header], 2) . '</strong>
										' . DEFAULT_CURRENCY . '
									</td>
								</tr>';

							}

					$return .= '
							<!--
							<tr><td colspan="5"><hr/></td></tr>
							<tr>
								<td style="background-color: #fff;" class="span1" colspan="4">Environment </td>
								<td style="background-color: #fff; text-align: right;" class="span2">
									<strong style="margin-left:25px;">' . number_format(DELIVERY_ENVIRONMENT, 2) . '</strong>
									' . DEFAULT_CURRENCY . '
								</td>
							</tr>
							<tr>
								<td style="background-color: #fff;" class="span1" colspan="4">Freight </td>
								<td style="background-color: #fff; text-align: right;" class="span2">
									<strong style="margin-left:25px;">' . number_format($delivery, 2) . '</strong>
									' . DEFAULT_CURRENCY . '
								</td>
							</tr>
							-->
							<tr><td style="background-color: #fff;" colspan="5"><hr/></td></tr>
							<tr>
								<td class="span1" colspan="4">Sub Total </td>
								<td class="span3" style="text-align: right;">
									<strong style="margin-left:25px;">' . number_format($total, 2) . '</strong>
									' . DEFAULT_CURRENCY . '
								</td>
							</tr>
							</table>
						</div>
					</p>
					<div style="clear:both"></both>';

		if ($allow_actions && $display) {
		$return .= '</hr>
					<p style="text-align: center;">
						<label data-type="offer-selector" for="offer-' . $_header . '" class="btn btn-info">Choose ' . $_header . ' offer
							<input style="display:none;" id="offer-' . $_header . '" type="radio" class="bnt btn-info" name="OFFER" value="' . $_header . '"/>
						</label>
					</p>';
		}
		$return .= '</div>
			</div>
		</li>';

	$box_idnex++;

	return $return;
}
