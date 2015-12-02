<div class="span12" style="margin-top: 30px;">
	<h2>Order history</h2>
	<hr />
	<table class="table table-bordered">
		<tr class="warning">
			<td><b>Order ID</b></td>
			<td><b>Customer Order No</b></td>
			<td><b>Total</b></td>
			<td><b>Order Date</b></td>
			<td class="span2" ><b>Actions</b></td>
		</tr>

<?
if (!empty($orders)) {
	// Outputting the orders that the user has placed
	$data_type_confirm = $Cart->getProductsCount() > 0;
	foreach ($orders as $_order) {
?>
		<tr <?=($_order['STATUS_ID'] == ORDER_OFFER) ? 'class="success"' : ''?>>
			<td><?=$_order['ORDER_ID']?></td>
			<td><?=$_order['EXTERNAL_ID']?></td>
			<td style="text-align: right;" ><?=number_format($_order['GRAND_TOTAL'], 2)?>&nbsp;
				<strong><?=$_order['CURRENCY_UOM']?></strong></td>
			<td><?=date_convert($_order['ORDER_DATE'], DEFAULT_DATE_FORMAT)?></td>
			<td>
				<a class="btn btn-small btn-info" href="/orders/view?id=<?=$_order['ORDER_ID']?>" >
					<i class="icon-eye-open icon-white"></i>
				</a>
				<!-- Check if there is something in the cart and then show an alert otherwise start a new order process -->
				<? if ($_order['STATUS_ID'] == ORDER_OFFER) { ?>
					<a class="btn btn-small btn-warning" href="/orders/cart?copy=<?=$_order['ORDER_ID']?>"
						<? if ($data_type_confirm){ ?>
							data-type="confirm" data-message="You have an order in progress this action will override it! Do you confirm?"
						<? } ?>
					>
						<i class="icon-play-circle icon-white"></i>
					</a>
				<? } ?>
			</td>
		</tr>
<?
	}
}
?>
	</table>
</div>
