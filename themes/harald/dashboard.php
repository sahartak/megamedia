<h2>Product Overview</h2>
<hr />

<div class="row">
	<div class="span12">
		<?=forms_admin_success($success); ?>
		<?=forms_admin_errors($errors); ?>
	</div>
</div>

<?
// Outputting the products that the user can buy
if (!empty($categories)) {
	$i = 0;
	foreach($categories as $_categoty){
		++$i;
?>

		<? if ($i==1 || $i%2 ==1) { ?>
			<div class="row">
		<? } ?>

				<div class="thumbnail span4">
					<img alt="" data-src="holder.js/300x200">
					<div class="caption">
						<h3><?=$_categoty['CATEGORY_NAME']?></h3>
						<p><?=$_categoty['DESCRIPTION']?></p>
						<p>
							<a class="btn btn-primary" href="/orders/order?PRODUCT_CATEGORY_ID=<?=$_categoty['PRODUCT_CATEGORY_ID']?>">Order</a>
						</p>
					</div>
				</div>

		<? if($i % 2 == 1){ ?>
				<!-- <div class="span1"></div> -->
		<? } ?>

		<? if($i % 2 == 0 && $i > 0){ ?>
			</div>
			<div>&nbsp;</div>
		<? } ?>

<?
	}
}
?>


<? if (isset($_SESSION['FAKE_LOGIN']) && $_SESSION['FAKE_LOGIN']) { ?>
<hr />
<div class="span12">
	<a class="btn btn-info" href="/users/logout"> Admin Page </a>
	<!--<a class="btn btn-info" href="/products/prices?PARTY_ID=<?=$_SESSION['user']['PARTY_ID']?>"> Set Prices </a>-->
</div>

</div>
<? } ?>
