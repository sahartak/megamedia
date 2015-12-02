<h4>Week number: <input type="text" value="<?=$order_weeks[3]?>" class="edit_week" data-type="3" /></h4>
<table class="table table-bordered table-striped additional_checkout">
	<tr>
		<th>Store</th>
		<th>Dimensions</th>
		<th>Amount</th>
		<th>Actions</th>
		<td class="hidden"><input class="add_price" type="hidden" value="100"></td>
	</tr>
	<?php $total = 0; $total_price = 0;
	foreach($rollup_orders as $rollup):?>
		<tr data-id="<?=$rollup['id']?>">
			<td class="add_store" data-id="<?=$rollup['store_id']?>"><?=$rollup['store_name']?></td>
			<td class="add_dimensions"><?=$rollup['width'],' x ',$rollup['height']; $total_price += ($rollup['amount'] * get_price_by_square($rollup['width'], $rollup['height'],$add_prices[3]))?></td>
			<td class="add_amount"><?=$rollup['amount']; $total += $rollup['amount'];?></td>
			<td>
				<button class="btn btn-danger additional_del" data-id="<?=$rollup['id']?>" title="delete"><i class="icon-trash icon-white"></i></button>
				<button class="btn btn-primary additional_edit" data-id="<?=$rollup['id']?>"><i class="icon-pencil icon-white"></i></button>
			</td>
		</tr>
	<?php endforeach;?>
	<tr>
		<th>Total</th>
		<td></td>
		<td class="add_total"><?=$total?></td>
		<td></td>
	</tr>
</table>