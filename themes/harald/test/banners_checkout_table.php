<h4>Week number:<input type="text" value="<?=$order_weeks[1]?>" class="edit_week" data-type="1" /></h4>
<table class="table table-bordered table-striped additional_checkout">
	<tr>
		<th>Store</th>
		<th>Dimensions</th>
		<th>Amount</th>
		<th>Ophaeng</th>
		<th>Material</th>
		<th>Actions</th>
		<td class="hidden"><input class="add_price" type="hidden" value="100"></td>
	</tr>
<?php $total = 0; $total_price = 0;
	foreach($banner_orders as $banner):?>
	<tr data-id="<?=$banner['id']?>">
		<td class="add_store" data-id="<?=$banner['store_id']?>"><?=$banner['store_name']?></td>
		<td class="add_dimensions"><?=$banner['width'],' x ',$banner['height']; $total_price += ($banner['amount'] * get_price_by_square($banner['width'], $banner['height'], $add_prices[1]));?></td>
		<td class="add_amount"><?=$banner['amount']; $total += $banner['amount'];?></td>
		<td class="add_hanging"><?=$banner['hanging_method']?></td>
		<td class="add_materials"><?=$materials[$banner['material_id']]?></td>
		<td>
			<button class="btn btn-danger additional_del" data-id="<?=$banner['id']?>" title="delete"><i class="icon-trash icon-white"></i></button>
			<button class="btn btn-primary additional_edit" data-id="<?=$banner['id']?>"><i class="icon-pencil icon-white"></i></button>
		</td>
	</tr>
<?php endforeach;?>
	<tr>
		<th>Total</th>
		<td></td>
		<td id="add_total"><?=$total?></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>
</table>