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

		<tr>
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
</div>
<script>
	$('#menu_dashboard').addClass('active');
</script>