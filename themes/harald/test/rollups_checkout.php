<div class="checkout_block">
	<?php require('rollups_checkout_table.php')?>
	<div class="row">
		<?php if(!isset($no_actions)) require('checkout_actions.php')?>
		<div class="span4 pull-right">
			Total Price : <b class="total_price"><?=round($total_price)?></b>
		</div>
	</div>
</div>