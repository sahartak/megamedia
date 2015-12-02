<div class="checkout_block">
	<?php require('banners_checkout_table.php')?>
	<div class="row">
		<?php if(!isset($no_actions)) require('checkout_actions.php')?>
		<div class="span4 pull-right">
			Total Price : <b class="total_price"><?=round($total_price)?></b>
		</div>
	</div>

	<div class="hidden">
		<?php foreach($stores_methods as $store_id => $methods):?>
			<select id="store_<?=$store_id?>">
				<?php foreach($methods as $method):?>
					<option value="<?=$method['id']?>"><?=$method['name']?></option>
				<?php endforeach;?>
			</select>
		<?php endforeach;?>
		<div class="material">
			<select>
				<option value="1">PVC Frontlight</option>
				<option value="2">PVC Mesh</option>
			</select>
		</div>
	</div>
</div>