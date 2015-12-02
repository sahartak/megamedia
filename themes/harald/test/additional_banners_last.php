<h2>Additional Banners</h2>
<div id="additional_content">
	<form method="post" id="additional_form">
		<input type="text" class="week_input" name="week_number" placeholder="Week number" value="<?php if(isset($week_number)) echo $week_number;?>" required="true" />
	<table class="table table-bordered table-striped" id="table">
		<tr>
			<th>Store</th>
			<th class="dim">Dimensions</th>
			<th>Amount</th>
			<th>Ophaeng</th>
			<th>Material</th>
		</tr>

	<?php $count = count($additional_orders); $i=1;
	foreach($additional_orders as $item):?>
		<tr>
			<td>
				<select name="store[]" required="required" class="store_selecting" data-value="<?=$item['store_id']?>">
					<option value="">Select store</option>
					<?php foreach ($stores as $store): ?>
						<option value="<?= $store['id'] ?>"> <?= $store['name'].' ('.$store['city'].')'; ?> </option>
					<?php endforeach; ?>
				</select>
			</td>
			<td>
				<input type="number" min="0" placeholder="width (cm)" name="width[]" value="<?=$item['width']?>" class="number">
				<span>X</span>
				<input type="number" min="0" placeholder="height (cm)" name="height[]"  value="<?=$item['height']?>"class="number">
			</td>
			<td>
				<input type="number" placeholder="Amount" class="number" name="amount[]"  value="<?=$item['amount']?>" min="0">
			</td>
			<td>
				<select name="ophaeng[]" class="ophaeng_select" required="required" data-value="<?=$item['ophaeng_id']?>">
				</select>
			</td>
			<td>
				<select name="material[]">
					<option value="1">PVC Frontlight</option>
					<option value="2" <?if($item['material_id']==2) echo 'selected'?>>PVC Mesh</option>
				</select>
				<?php if($i==$count):?>
					<button class="btn btn-info btn-custom">+</button>
				<?php endif;?>
			</td>
		</tr>
	<?php $i++; endforeach;?>
	</table>

	<p class="pull-right">
		<button class="btn btn-success" id="success">Continue</button> <a class="btn btn-info" href="/">Back</a>
	</p>
	</form>
</div>
<div class="hidden">
<?php foreach($stores_methods as $store_id => $methods):?>
	<select id="store_<?=$store_id?>">
	<?php foreach($methods as $method):?>
		<option value="<?=$method['id']?>"><?=$method['name']?></option>
	<?php endforeach;?>
	</select>
<?php endforeach;?>
</div>
<table class="hidden">
	<tr id="additional_row">
		<td>
			<select name="store[]" required="required" class="store_selecting">
				<option value="">Select store</option>
				<?php foreach ($stores as $store): ?>
					<option value="<?= $store['id'] ?>"> <?= $store['name'].' ('.$store['city'].')'; ?> </option>
				<?php endforeach; ?>
			</select>
		</td>
		<td>
			<input type="number" min="0" placeholder="width (cm)" name="width[]" class="number">
			<span>X</span>
			<input type="number" min="0" placeholder="height (cm)" name="height[]" class="number">
		</td>
		<td>
			<input type="number" placeholder="Amount" class="number" name="amount[]" min="0">
		</td>
		<td>
			<select name="ophaeng[]" class="ophaeng_select" required="required">
			</select>
		</td>
		<td>
			<select name="material[]">
				<option value="1">PVC Frontlight</option>
				<option value="2">PVC Mesh</option>
			</select>
			<button class="btn btn-info btn-custom">+</button>
		</td>
	</tr>
</table>
<script>
	$('#menu_dashboard').addClass('active');
	$('.store_selecting').each(function() {
		var value = $(this).attr('data-value');
		$(this).find('option[value='+value+']').prop('selected', true);
		var store_id = $(this).val();
		var options = $('#store_'+store_id).html();
		$(this).closest('tr').find('.ophaeng_select').html(options);
	});
	$('.ophaeng_select').each(function() {
		var value = $(this).attr('data-value');
		$(this).find('option[value='+value+']').prop('selected', true);
	});
</script>