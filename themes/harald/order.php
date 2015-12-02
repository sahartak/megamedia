<div class="row">
	<div class="span12">
		<h3><?=$product['PRODUCT_NAME']?> </h3>
		<h6><?=$product['DESCRIPTION']?></h6>
		<hr />
	</div>
</div>

<div class="row">
	<div class="span12">
		<?=forms_admin_success($success); ?>
		<?=forms_admin_errors($errors); ?>
	</div>
</div>

<div class="row">
	<form method="post" action="/orders/calculator?PRODUCT_CATEGORY_ID=<?=$_GET['PRODUCT_CATEGORY_ID']?>" class="form-horizontal">
		<div class="span12">
			<table class="table table-bordered span6" id="products">
				<tr class="warning">
					<td class="span1"><b>#</b></td>
					<td class="span2"><b>Motives</b></td>
					<td class="span2"><b>Amount</b></td>
					<?
						if (!empty($assocs)) {
							foreach($assocs as $_assoc) {
								?> <td class="span2"><b><?=$_assoc['PRODUCT_NAME']?></b></td> <?
							}
						}
					?>

				</tr>

				<?php for ($i=1; $i <= ROWS_PER_CALCULATION; $i++) { ?>
				<tr>
					<td><?=$i?></td>
					<td><input type="text" data-type="motives" class="input-small" name="MOTIVES[<?=$i?>]" value="1"></td>
					<td><input type="text" data-type="runs" class="input-small" name="RUNS[<?=$i?>]" value="<?=isset($_POST['RUNS'][$i])?$_POST['RUNS'][$i]:0?>"></td>
					<?
						if (!empty($assocs)) {
							foreach($assocs as $index=>$_assoc) {
								?>
									<td class="span2">
										<input type="radio" name="VARIANT[<?=$i?>]" value="<?=$_assoc['PRODUCT_ID']?>" <?=isset($_POST['VARIANT'][$i]) && $_POST['VARIANT'][$i] == $_assoc['PRODUCT_ID'] ?'checked':''?>
											<? if (count($assocs) == 1 ) { ?> checked <? } ?>
										/>
									</td> <?
							}
						}
					?>
				</tr>
				<? } ?>

				<? if (isset($_POST['MOTIVES']) && count($_POST['MOTIVES']) > ROWS_PER_CALCULATION) { ?>
					<?php for ($i = ROWS_PER_CALCULATION + 1; $i<=count($_POST['MOTIVES']); $i++) { ?>
					<tr>
						<td><?=$i?></td>
						<td><input type="text" data-type="motives" class="input-small" name="MOTIVES[<?=$i?>]" value="1"></td>
						<td><input type="text" data-type="runs" class="input-small" name="RUNS[<?=$i?>]" value="<?=isset($_POST['RUNS'][$i])?$_POST['RUNS'][$i]:0?>"></td>
						<?
							if (!empty($assocs)) {
								foreach($assocs as $index=>$_assoc) {
									?> <td class="span2"><input type="radio" name="VARIANT[<?=$i?>]" value="<?=$_assoc['PRODUCT_ID']?>" <?=isset($_POST['VARIANT'][$i]) && $_POST['VARIANT'][$i] == $_assoc['PRODUCT_ID'] ?'checked':''?> /></td> <?
								}
							}
						?>
					</tr>
					<? } ?>
				<? } ?>

			</table>
			<table class="table table-bordered span6">
				<tr class="info">
					<td class="span1"><b>Total:</b></td>
					<td class="span2"><b id="total-motives"></b></td>
					<td class="span2"><b id="total-runs"></b></td>
					<?
						if (!empty($assocs)) {
							foreach($assocs as $index=>$_assoc) {
								?> <td class="span2"></td> <?
							}
						}
					?>
				</tr>
			</table>

			<div style="clear: both"></div>

			<div class="control-group">
				<div class="controls">
					<button class="btn btn-primary" type="submit"><i class="icon-list-alt icon-white"></i> Calculate Prices</button>
					<button class="btn btn-success" type="button" id="add-payment"><i class="icon-plus icon-white"></i> Add Line</button>
					<a data-type="confirm" data-message="Are you sure you want to reset everything?" href="/orders/order?PRODUCT_CATEGORY_ID=<?=$_GET['PRODUCT_CATEGORY_ID']?>" class="btn btn-primary btn-danger" type="button">Reset</a>
					<a href="/dashboard/dashboard" class="btn btn-primary btn-danger" type="button">Cancel</a>
				</div>
  			</div>
  		</div>
	</form>
</div>

<script>
$(function() {
	var current = <?=ROWS_PER_CALCULATION?> + <?=isset($_POST['MOTIVES']) && count($_POST['MOTIVES']) > ROWS_PER_CALCULATION ? count($_POST['MOTIVES']) - ROWS_PER_CALCULATION : 0?>;

	$('#add-payment').click(function(){
		current++;
		var template = '<tr>' + $('#payments-template').html() + '</tr>';
		for (i = <?=count($assocs)?> + 5; i > 0; i--) {
			template = template.replace('{{row-number}}', + current);
		}
		template = template.replace('{{motives-number}}', + 1);
		$('#products tbody').append(template);
		attach_on_change($('input[data-bind="append-' + current + '"]'));
	});

	$('input[data-type="motives"]').on('focus', function() {
		$(this).blur();
	});
});

function attach_on_change(obj) {
	obj.bind("keyup", calculate_total_visuals);
	obj.bind("focus", function() {
		if ($(this).attr('data-type') == 'motives') {
			$(this).blur();
		}
	});
	obj.bind("click", function () {
		$(this).select();
	});

	calculate_total_visuals();
}

function calculate_total_visuals() {
	var runs = 0;
	var motives = 0;

	$('input[data-type="runs"]').each(function(){
		runs += parseInt($(this).val());
	});

	$('input[data-type="motives"]').each(function(){
		if ($(this).is(':visible')) {
			motives += parseInt($(this).val());
		}
	});

	$('#total-runs').html(runs);
	$('#total-motives').html(motives);
}

attach_on_change($('input[data-type="runs"]'));
attach_on_change($('input[data-type="motives"]'));
calculate_total_visuals();
</script>

<div type="html/template" style="display: none;">
	<table>
		<tbody id="payments-template">
			<tr>
				<td>{{row-number}}</td>
				<td><input type="text" data-type="motives" class="input-small" data-bind="append-{{row-number}}" name="MOTIVES[{{row-number}}]" value="{{motives-number}}"></td>
				<td><input type="text" data-type="runs" class="input-small" data-bind="append-{{row-number}}" name="RUNS[{{row-number}}]" value="0"></td>
				<?
					if (!empty($assocs)) {
						foreach($assocs as $index=>$_assoc) {
							?>
								<td class="span2">
									<input type="radio" name="VARIANT[{{row-number}}]"  value="<?=$_assoc['PRODUCT_ID']?>"
										<? if (count($assocs) == 1 ) { ?> checked <? } ?>
									/>
								</td> <?
						}
					}
				?>
			</tr>
		</tbody>
	</table>
</div>
