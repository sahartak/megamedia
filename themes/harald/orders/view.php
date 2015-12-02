<div class="row">
	<div class="span12">
		<h3>View Order</h3>
		<hr />
	</div>
</div>

<div class="row">
	<div class="span12">
		<?=forms_admin_errors($errors); ?>
	</div>
</div>

<? if(empty($errors)){ ?>
	<div class="row">
		<div class="span6">
			<ul class="unstyled">
				<?

					$total_environment = count($order_cart['PRODUCTS']) * DELIVERY_ENVIRONMENT;
					$total_delivery = 0;
					foreach ($order_cart['PRODUCTS'] as $index => $_row) {
						list($headers, $rows, $variants, $copies, $delivery) = html_offer_box($_row['PRODUCTS'], $order_cart['ADDRESS'][$index]);
						$total_delivery += $delivery;
						echo html_build_offer_box($rows, $variants, $copies, $delivery, $_row['OFFER'], $_row['PRODUCTS']['MOTIVES'], false, false, $_row['PRODUCTS']['RUNS'], $_row['PRODUCTS']['VARIANT']);
						echo '<li class="span4"><div> </div></li>';
					}
				?>
				<li class="span4"><hr /></li>
				<li class="span5">
					<table class="nohover" style="font-size: 15px; margin-left: 20px;">
						<tr>
							<td style="width: 100px;">All Products: </td>
							<td style="width: 160px; text-align: right;">
								<strong><?=number_format($order_cart['GRAND_TOTAL'], 2)?></strong>
								<?=DEFAULT_CURRENCY?>
							</td>
						</tr>
						<!-- <tr>
							<td>Environment: </td>
							<td style="text-align: right;">
								<strong><?=number_format($total_environment, 2)?></strong>
								<?=DEFAULT_CURRENCY?>
							</td>
						</tr> -->
						<tr>
							<td>All Freight + Delivery: </td>
							<td style="text-align: right;">
								<strong><?=number_format(DELIVERY_ENVIRONMENT + $total_delivery + $system_settings['ORDER_FEE'], 2)?></strong>
								<?=DEFAULT_CURRENCY?>
							</td>
						</tr>
						<tr>
							<td>Grand Total: </td>
							<td style="text-align: right;">
								<strong>
									<?=number_format($order_cart['GRAND_TOTAL'] + DELIVERY_ENVIRONMENT + $total_delivery + $system_settings['ORDER_FEE'], 2)?>
								</strong>
								<?=DEFAULT_CURRENCY?>
							</td>
						</tr>
					</table>
				</li>
			</ul>
		</div>
		<div class="span6">
			<!-- The actual form data to be filled in -->
			<fieldset>
				<legend>Optional Information</legend>
				<label><b>Your reference:</b> <?=$order['EXTERNAL_ID']?></label>
				<? if (!empty($shipment)) { ?>
					<label><b>Additional email:</b> <?=$shipment['HANDLING_INSTRUCTIONS']?></label>
				<? } ?>
				<label><b>Extra Information:</b> <?=$order['COMMENTS']?></label>
				<br /><br />
				<legend>Address</legend>
				<? if (!empty($address)) { ?>
					<label><?=$address?></label>
					<br /><br />
					<? 	if (!empty($documents)){ ?>
						<legend>Requisition files</legend>
						<?
							$i=1;
							foreach ($documents as $_doc) {
						?>
							<a target="_blank" href="<?=FILES_DISPLAY_PATH . basename($_doc['DOCUMENT_LOCATION']);?>">Download requisition <?=$i++ . ' (' . basename($_doc['DOCUMENT_LOCATION']) . ')';?></a><br />
					<?
							}
						}
					?>
				<? } ?>
			</fieldset>
		</div>
	</div>
<? } ?>
