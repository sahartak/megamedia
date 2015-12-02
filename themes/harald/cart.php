<div class="row">
	<div class="span12">
		<h3>Current Offer</h3>
		<hr />
	</div>
</div>

<div class="row">
	<div class="span12">
		<?=forms_admin_errors($errors); ?>
	</div>
</div>

<form method="post" action="/orders/cart" enctype="multipart/form-data">
	<input type="hidden" value="" name="offer-state" id="is-offer"/>
	<div class="row">
		<div class="span6">
			<ul class="unstyled">
				<?
					$total_environment = $Cart->getProductsCount() * DELIVERY_ENVIRONMENT;
					$total_delivery = 0;

					foreach ($Cart->getProducts() as $i => $_row) {

						list($headers, $rows, $variants, $copies, $delivery) = html_offer_box($_row['PRODUCTS'], $Cart->deliveryDistributionAt($i));
						$total_delivery += $delivery;
						echo html_build_offer_box($rows, $variants, $copies, $delivery, $_row['OFFER'], $_row['PRODUCTS']['MOTIVES'], false, true, $_row['PRODUCTS']['RUNS'], $_row['PRODUCTS']['VARIANT']);
						echo '<li class="span4"><div> </div></li>';
					}
				?>
				<li class="span4"><hr /></li>
				<li class="span5">
					<table class="nohover" style="font-size: 15px; margin-left: 20px;">
						<tr>
							<td style="width: 100px;">All Products: </td>
							<td style="width: 160px; text-align: right;">
								<strong>
									<?=number_format($Cart->getTotal(), 2)?>
								</strong>
								<?=DEFAULT_CURRENCY?>
							</td>
						</tr>
						<tr>
							<td>All Freight + Environment: </td>
							<td style="text-align: right;">
								<strong>
									<?=number_format($Cart->getTotal() ? DELIVERY_ENVIRONMENT + $total_delivery + $system_settings['ORDER_FEE'] : 0, 2)?>
								</strong>
								<?=DEFAULT_CURRENCY?>
							</td>
						</tr>
						<tr>
							<td>Grand Total: </td>
							<td style="text-align: right;">
								<strong>
									<?=number_format($Cart->getTotal() ? $Cart->getTotal() + DELIVERY_ENVIRONMENT + $total_delivery + $system_settings['ORDER_FEE'] : 0, 2)?>
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
					<label>Your reference (optional)</label>
					<input name="EXTERNAL_ID" class="span5" type="text" placeholder="Your reference (optional)"
						<? if (isset($offer_meta)) { ?> value="<?=$offer_meta['EXTERNAL_ID']?>" <? } ?>
					>


					<label>Additional notification email (optional)</label>
					<input name="HANDLING_INSTRUCTIONS" class="span5" type="text" placeholder="Additional notification email (optional)"
						<? if (isset($offer_shipment)) { ?> value="<?=$offer_shipment['HANDLING_INSTRUCTIONS']?>" <? } ?>
					>

					<label>Extra Information (optional)</label>
					<textarea name="COMMENTS"  class="span5" rows="5" placeholder="Extra Information (optional)"><? if (isset($offer_meta)) { echo $offer_meta['COMMENTS']; } ?></textarea>

					<legend>Address Selection</legend>
				</fieldset>

				<!-- Changing the values of the radio buttons will have effect because they are used tp
				select the delivery address for the order -->
				<ul class="nav nav-tabs" id="address-tab">
					<li class="active">
						<a href="#company" for="company-address-radio">Company Address</a>
						<input type="radio" style="display: none;" id="company-address-radio" name="address" value="company" checked/>
					</li>
					<!-- <li>
						<a href="#requisition" for="requisition-address-radio">Requisition Address</a>
						<input type="radio" style="display: none;"  id="requisition-address-radio" name="address" value="requisition"/>
					</li> -->
					<li>
						<a href="#alternative" for="alternative-address-radio">Alternative Address</a>
						<input type="radio" style="display: none;"  id="alternative-address-radio" name="address" value="alternative"/>
					</li>
				</ul>

				<div class="tab-content">
					<div class="tab-pane active" id="company">
						<div class="well well-small">We are going to use your company address for delivery</div>
						<h5><?=contact_generate_string($contacts, '<br />')?></h5>
					</div>
					<div class="tab-pane" id="requisition">
						<div class="well well-small">
							We are going to use the address from the uploaded files for delivery. <br />
							At least one requisition needs to be uploaded!
						</div>
					</div>
					<div class="tab-pane" id="alternative">
						<div class="well well-small">
							We are going to use the address filled
							in bellow for for delivery or select from
							the previously used addresses
						</div>
						<fieldset>
							<select name="OLD_ALTERNATIVE_CONTACT">
								<?=forms_draw_select($alternative, 'CONTACT_MECH_ID', 'INFO_STRING', '', 'New alternative address'); ?>
							</select>

							<label>Company name <b class="mandatory">*</b></label>
							<input class="span5" type="text" placeholder="Company name" name="<?=ALTERNATIVE_CONTACT?>[NAME]"
									value="<?=isset($_POST[ALTERNATIVE_CONTACT][CNT_ATTR_NAME]) ? $_POST[ALTERNATIVE_CONTACT][CNT_ATTR_NAME] : '';?>">
							<label>Streetname, number <b class="mandatory">*</b></label>
							<input class="span5" type="text" placeholder="Streetname, number" name="<?=ALTERNATIVE_CONTACT?>[STREET]"
									value="<?=isset($_POST[ALTERNATIVE_CONTACT][CNT_ATTR_STREET]) ? $_POST[ALTERNATIVE_CONTACT][CNT_ATTR_STREET] : '';?>">
							<label>Postal number <b class="mandatory">*</b></label>
							<input class="span5" type="text" placeholder="Postal number" name="<?=ALTERNATIVE_CONTACT?>[POSTAL]"
									value="<?=isset($_POST[ALTERNATIVE_CONTACT][CNT_ATTR_POSTAL]) ? $_POST[ALTERNATIVE_CONTACT][CNT_ATTR_POSTAL] : '';?>">
							<label>City <b class="mandatory">*</b></label>
							<input class="span5" type="text" placeholder="City" name="<?=ALTERNATIVE_CONTACT?>[CITY]"
									value="<?=isset($_POST[ALTERNATIVE_CONTACT][CNT_ATTR_CITY]) ? $_POST[ALTERNATIVE_CONTACT][CNT_ATTR_CITY] : '';?>">
							<label>Country <small>(if not denmark)</small></label>
							<input class="span5" type="text" placeholder="Country" name="<?=ALTERNATIVE_CONTACT?>[COUNTRY]">
						</fieldset>
					</div>
				</div>

				<script>
					$('#address-tab a').click(function (e) {
						e.preventDefault();
						$(this).tab('show');
						var radio = $(this).attr('for');
						$('#' + radio).prop('checked', true);
					})
				</script>
		</div>
	</div>

	<? if ($Cart->getProductsCount()) { ?>
		<!-- If there are no products in the cart we do not display the buttons -->
		<div class="row">
			<div class="span12">
				<hr />
				<button class="btn btn-success" type="submit" id="approve-order">Approve Order</button>
				<!-- Before saving offers we set a hidden field to indicate that to the backend -->
				<a class="btn btn-success" id="save-offer">Save Offer</a>
				<a class="btn btn-info" type="button" href="/dashboard/dashboard">Continue</a>
				<button class="btn btn-inverse" type="button">Copy</button>
				<a href="/orders/cart?RESET" class="btn btn-danger" type="button" data-type="confirm">Clear</a>
				<!-- <button class="btn " type="button">Resend</button> -->
			</div>
		</div>
	<? } ?>
</form>

<script type="text/javascript">
$(document).ready(function(){
	$('a[data-toggle="tooltip"]').tooltip();
	<? if (!empty($_POST) && isset($_POST['address']) && !empty($_POST['address'])) { ?>
		var id = 'a[href="#<?=$_POST['address']?>"]';
		$(id).trigger('click');
	<? } ?>

	$('#save-offer').on('click', function() {
		$('#is-offer').val('true');
		$('#approve-order').trigger('click');
	});
});
</script>
