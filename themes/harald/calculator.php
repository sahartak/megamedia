<div class="row">
	<div class="span12">
		<h3>Price overview</h3>
		<hr />
	</div>
</div>

<div class="alert alert-error" id="offer-error" style="display: none;">
	Please select an offer before you can add it to the order!
</div>

<div class="row">
	<div class="span12">
		<ul class="thumbnails">
			<form action="/orders/cart" id="order-form" method="post">
				<!-- This input will store the action to be performed after adding the offer to the cart-->
				<input type="hidden" id="after-add" name="after-add" />
				<input type="hidden" id="after-add" name="PRODUCT_CATEGORY_ID" value="<?=$_GET['PRODUCT_CATEGORY_ID']?>"/>

				<?
					foreach ($headers as $_header) {
						echo html_build_offer_box($rows, $variants, $copies, $delivery, $_header, $_POST['MOTIVES'], true, true, $_POST['RUNS'], $_POST['VARIANT']);
					}
				?>

				<!-- Address Modal -->
				<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						<h3 id="myModalLabel">Delivery Address</h3>
					</div>
					<div class="modal-body">
						<p>By providing the number of delivery addresses you make the total price much more realistic! Max. 5 Addesses.
						Total amount of runs: <b id="total-runs"><?=array_sum($_POST['RUNS']); ?></b><br /></p>
						Number of delivery addresses: <input type="text" class="input-small" id="delivery-address-count" style="margin-bottom: 10px;"/>
						<table class="table table-bordered table-striped" id="develiry-address-table-body">
						</table>
					</div>
					<div class="modal-footer">
						<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
						<button class="btn btn-primary">Add to order</button>
					</div>
				</div>

			</form>
		</ul>

		<hr />

		<!-- Do not change the IDs of the buttons as they are used to indicate to where the cart should redirect the customer -->
		<!-- <button class="btn btn-primary btn-large btn-success" type="button" id="add-to-order">Add to Order</button> -->
		<button class="btn btn-primary btn-large btn-success" type="button" id="add-to-order">Add to Order</button>
		<!-- <button class="btn btn-primary btn-large btn-warning" type="button" id="go-back">Add and Go Back</button> -->
		<button class="btn btn-primary btn-large btn-inverse" type="button" id="continue">Add and Continue</button>
		<a href="/orders/order?PRODUCT_CATEGORY_ID=<?=$_GET['PRODUCT_CATEGORY_ID']?>&recalculate" class="btn btn-primary btn-large btn-danger" type="button">Go Back</a>

		<div style="clear: both"></div>
		<br />
		<div class="well well-small" style="marign-top: 10px;">
			<ul class="unstyled">
				<li><span class="label label-success">Add to Order</span> - Will add selection to order and display the order</li>
				<!-- <li><span class="label label-warning">Add and Go Back</span> - Will add selection to order and display the form for a new calculation of <?=$product['PRODUCT_NAME']?></li> -->
				<li><span class="label label-inverse">Add and Continue</span> - Will add the selection to the order and take you back to products overview</li>
				<li><span class="label label-important">Go Back</span> - Will take you back to the current calculation with option to change parameters</li>
			</ul>
		</div>

	</div>
</div>

<script type="text/javascript">
$(function() {
	// Attacing submit form events on the buttons
	$('#add-to-order').on('click', submit_form);
	$('#go-back').on('click', submit_form);
	$('#continue').on('click', submit_form);

	// Colouring for the select radio buttons
	$('label[data-type="offer-selector"]').click(function(){
		$('label[data-type="offer-selector"]').removeClass('btn-info');
		$('label[data-type="offer-selector"]').removeClass('btn-success');
		$('label[data-type="offer-selector"]').addClass('btn-danger');
		$(this).addClass('btn-success');
		$(this).removeClass('btn-danger');
	});

	// Calculating and building the address boxes
	$('#delivery-address-count').keyup(function(){
		var count = $(this).val() * 1;

		if (count > 5) {
			count = 5;
			$('#delivery-address-count').val(count);
		}

		// If there are inputs with address we unbind the events attached to them
		$('input[data-type="per-address-runs"]').each(function() {
			if ($(this).is(':visible')) {
				$(this).unbind('keyup');
			}
		});

		// Clearing and building the modal table with address inputs
		$('#develiry-address-table-body').html('');
		$('#develiry-address-table-body').append($('#delivery-address-header').html())

		for(var i=1; i<=count; ++i) {
			$('#develiry-address-table-body').append($('#delivery-address-template').html().replace('{{number}}', i));
		}

		$('#develiry-address-table-body').append($('#delivery-address-total').html().replace('{{total}}', 0));

		// Filling by deafult
		if (count > 0) {
			var total = $('#total-runs').html() * 1;
			var per_input = Math.floor(total / count);
			$('input[data-type="per-address-runs"]').each(function() {
				if ($(this).is(':visible')) {
					// Set the value of the input
					$(this).val(per_input);

					// Bind the keyup event to the inputs
					$(this).bind('keyup', function() {
						$(this).parent().removeClass('info');
						$(this).parent().addClass('success');
						calculate_total_addresses(total);
					});
				}
			});

			var total_each_address = 0;
			$('input[data-type="per-address-runs"]').each(function() {
				total_each_address += $(this).val() * 1;
			});

			diff = total - total_each_address;
			i = 0;
			inputs = $('input[data-type="per-address-runs"]').toArray();
			while (diff != 0) {
				$(inputs[i]).val($(inputs[i]).val() * 1 + 1);
				diff--;
				i++;
			}

			$('#total-table').html(total);
		}
	});
});

function submit_form() {
	var check = false
	// Checked of an offer has been selected
	$("input:radio[name=OFFER]").each(function(){
		if($(this).is(':checked')) {
			check = true;
		}
	});

	if (!check) {
		$('#offer-error').css('display', 'block');
		return;
	}

	// Set the hidden field with the action after submit
	$('#after-add').val($(this).attr('id'));

	// Submit the form
	//$('#order-form').submit();
	$('#modal-spooner').trigger('click');

}

function calculate_total_addresses(must_be) {
	var total = 0;
	$('input[data-type="per-address-runs"]').each(function() {
		total += $(this).val() * 1;
	});
	console.log(total);
	$('#total-table').html(total);
	if (total != must_be) {
		$('#total-table').css('color', '#f00');
	} else {
		$('#total-table').css('color', '#0f0');
	}
}

// If there is only one production line then we hide the select button
// and automatically select it for the user
if ($('input:radio[name=OFFER]').size() == 1) {
	$('input:radio[name=OFFER]').attr('checked', 'checked');
	$('input:radio[name=OFFER]').parent().parent().css('display', 'none');
}
</script>

<!-- <pre>
<?php var_dump($copies); ?>
</pre> -->

<!-- Address Modal -->
<button class="btn btn-primary btn-large btn-success" type="button" id="modal-spooner" href="#myModal" data-toggle="modal">Add to Order</button>

<!-- Delivery address template -->
<table id="delivery-address-template" style="display: none;">
	<tr>
		<td>{{number}}</td>
		<td>
			<div class="control-group info" style="margin-bottom: 0px;">
				<input type="text" value="0" name="delivery_address[]" data-type="per-address-runs" class="input-small" style="margin-bottom: 0;"/></td>
			</div>
	</tr>
</table>

<table id="delivery-address-header" style="display: none;">
	<tr>
		<th>Address</th>
		<th>Runs to be delivered</th>
	</tr>
</table>

<table id="delivery-address-total" style="display: none;">
	<tr>
		<td></td>
		<td id="total-table">{{total}}</td>
	</tr>
</table>
