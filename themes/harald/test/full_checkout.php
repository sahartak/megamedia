<?php
$no_actions = true;
$full_price = 0;
if($campaign_orders) {
	echo '<h2>Campaign</h2>';
	require('campaign_checkout.php');
	$full_price += $total_price;
}

if($banner_orders) {
	echo '<h2>Banners</h2>';
	require('banners_checkout.php');
	$full_price += $total_price;
}

if($poster_orders) {
	echo '<h2>Posters</h2>';
	require('posters_checkout.php');
	$full_price += $total_price;
}

if($rollup_orders) {
	echo '<h2>Roll ups</h2>';
	require('rollups_checkout.php');
	$full_price += $total_price;
}

require('checkout_actions.php');