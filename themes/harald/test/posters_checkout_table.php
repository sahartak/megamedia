<h4>Week number: <input type="text" value="<?=$order_weeks[2]?>" class="edit_week" data-type="2" /></h4>
<table class="table table-bordered table-striped additional_checkout">
	<tr>
		<th>Store</th>
		<th>Dimensions</th>
		<th>Amount</th>
		<th>Material</th>
		<th>Actions</th>
		<td class="hidden"><input class="add_price" type="hidden" value="100"></td>
	</tr>
	<?php $total = 0; $total_price = 0;
	foreach($poster_orders as $poster):?>
		<tr data-id="<?=$poster['id']?>">
			<td class="add_store" data-id="<?=$poster['store_id']?>"><?=$poster['store_name']?></td>
			<td class="add_dimensions"><?=$poster['width'],' x ',$poster['height']; $total_price += ($poster['amount'] * get_price_by_square($poster['width'], $poster['height'], $add_prices[2]));?></td>
			<td class="add_amount"><?=$poster['amount']; $total += $poster['amount'];?></td>
			<td class="add_materials"><?=$materials[$poster['material_id']]?></td>
			<td>
				<button class="btn btn-danger additional_del" data-id="<?=$poster['id']?>" title="delete"><i class="icon-trash icon-white"></i></button>
				<button class="btn btn-primary additional_edit" data-id="<?=$poster['id']?>"><i class="icon-pencil icon-white"></i></button>
			</td>
		</tr>
	<?php endforeach;?>
	<tr>
		<th>Total</th>
		<td></td>
		<td class="add_total"><?=$total?></td>
		<td></td>
		<td></td>
	</tr>
</table>