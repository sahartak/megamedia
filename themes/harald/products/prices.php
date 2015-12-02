<div class="row" style="margin-left: 5px;">
	<h3>
		Customer prices: <?=$person['FIRST_NAME']?> <?=$person['LAST_NAME']?>
		<? if ($party_id) { ?>
		<div class="pull-right">
			<a href="/users/index?VIEW=<?=$party_id?>" class="btn btn-small btn-info"><i class="icon-shopping-cart icon-white"></i> Customer homepage</a>
		</div>
		<? } ?>
	</h3>
	<hr />

	<? if (!$party_id) { ?>
		<div class="alert alert-info">
			<strong>Warning!</strong> You are changing the default prices for the products. All changes will affect
			newly registered users and current user that have not set specific prices.
		</div>
	<? } ?>
</div>

<div class="row" style="margin-left: 5px;">
	<form class="form-horizontal" type="post" action="/products/prices<? if ($party_id){ ?>?PARTY_ID=<?=$party_id?><? } ?>" method="post">
		<div class="accordion" id="accordion2">
			<?
				$i = 0;
				foreach ($categories as $_cat) {
					$i++;
					// We need to clear this to build the rest of the form
					// so the products features do not interfere with each other
					$main_product_feature_ids = [];

					$product = products_get_by_type($_cat['PRODUCT_CATEGORY_ID'], PRODUCT_VISUAL);
					$assocs = product_assoc_get($product['PRODUCT_ID'], PRODUCT_ASSOC_VARIANT);
					$features = product_get_features($assocs[0]['PRODUCT_ID']);
					$features_count = count($features);
			?>
					<div class="accordion-group">
					<div class="accordion-heading">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse<?=$i?>">
							<?=$_cat['CATEGORY_NAME']?>
						</a>
					</div>
					<div id="collapse<?=$i?>" class="accordion-body collapse in">
						<div class="accordion-inner">
							<table class="table table-bordered">
								<tr class="warning">
									<td class="span3" rowspan="2"><b>Motive variants</b></td>
									<td colspan="<?=$features_count?>"><b>Prices of production lines</b></td>
								</tr>
								<tr class="warning">
									<?
										foreach ($features as $_feature) {
											$main_product_feature_ids[] = $_feature['PRODUCT_FEATURE_ID'];
									?>
										<td class="span<?=floor(9/$features_count)?>"><b><?=$_feature['PRODUCT_FEATURE_ID']?></b></td>
									<?
										}
									?>
								</tr>

								<?
									foreach ($assocs as $_assoc) {
										$features = product_get_features($_assoc['PRODUCT_ID']);
								?>
										<tr>
											<td><?=$_assoc['PRODUCT_ID']?> (<?=$_assoc['PRODUCT_NAME']?>)</td>
											<?
												foreach($features as $index => $_feature) {
													$copies = product_assoc_get($_assoc['PRODUCT_ID'], PRODUCT_ASSOC_PAIR, true);
													$copies_features = product_get_features($copies[0]['PRODUCT_ID']);
													$price = product_feature_price_get($_feature['PRODUCT_FEATURE_APPL_ID'], PAGE_PRICE_TYPE, $party_id);
													$price = isset($price['PRICE']) ? $price['PRICE'] : 0.00;
											?>
													<td>
														<div class="control-group">
															<label class="control-label">Motive: </label>
															<div class="controls">
																<div class="input-append">
																	<input value="<?=$price?>" name="APPL_TYPE_ID[<?=$_feature['PRODUCT_FEATURE_APPL_ID']?>]" class="span1" id="appendedInput" type="text">
																	<span class="add-on"><?=DEFAULT_CURRENCY?></span>
																</div>
															</div>
														</div>

														<?
															foreach ( $copies_features as $_copy_feat) {
																if ($_copy_feat['PRODUCT_FEATURE_ID'] == $main_product_feature_ids[$index]) {
																// if (in_array($_copy_feat['PRODUCT_FEATURE_ID'], $main_product_feature_ids)) {
																	$copy_price = product_feature_price_get($_copy_feat['PRODUCT_FEATURE_APPL_ID'], PAGE_PRICE_TYPE, $party_id);
																	$copy_price = isset($copy_price['PRICE']) ? $copy_price['PRICE'] : 0.00;

														?>
																	<div class="control-group">
																		<label class="control-label">Runs: </label>
																		<div class="controls">
																			<div class="input-append">
																				<input value="<?=$copy_price?>" name="APPL_TYPE_ID[<?=$_copy_feat['PRODUCT_FEATURE_APPL_ID']?>]" class="span1" id="appendedInput" type="text">
																				<span class="add-on"><?=DEFAULT_CURRENCY?></span>
																			</div>
																		</div>
																	</div>
														<?
																	break;
																}
															}
														?>
													</td>
											<?
												}
											?>
										</tr>
								<?
									}
								?>

							</table>
						</div>
					</div>
					</div>
			<?
				}
			?>
		</div>
		<hr />
		<button class="btn btn-primary btn-success" type="submit"><i class="icon-hdd icon-white"></i> Save</button>
		<a class="btn btn-primary btn-danger" href="/users/index"><i class="icon-backward icon-white"></i> Back</a>
	</form>
</div>
