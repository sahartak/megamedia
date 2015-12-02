<div class="span1"></div>
<div class="span6">
	<a class="btn btn-success" href="/test/aprove_order">Approve order</a> &nbsp;
	<a class="btn btn-primary" href="/">Continue</a> &nbsp;
	<a class="btn btn-danger" href="/test/clear_order">Clear</a> &nbsp;
</div>
<?php if (isset($no_actions)):?>
	<div class="span4 pull-right">
		Full Price : <b id="full_price"><?=round($full_price)?></b>
	</div>
<?php endif;?>