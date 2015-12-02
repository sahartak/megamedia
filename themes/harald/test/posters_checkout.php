<div class="checkout_block">
	<?php require('posters_checkout_table.php')?>
	<div class="row">
		<?php if(!isset($no_actions)) require('checkout_actions.php')?>
		<div class="span4 pull-right">
			Total Price : <b class="total_price"><?=round($total_price)?></b>
		</div>
	</div>
	<div class="hidden material">
		<select>
			<option value="3">Blueback</option>
			<option value="4">Citylight</option>
		</select>
	</div>
</div>