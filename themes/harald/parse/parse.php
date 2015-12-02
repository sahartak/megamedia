<? if (!empty($Offer)) { ?>
	<table class="table table-bordered">
		<tr>
			<td>Subject: </td>
			<td><b><?=$Offer->enquery_subject?></b></td>
		</tr>
		<tr>
			<td>Delivery Date: </td>
			<td><b>
				<?
					$products = $Offer->getProducts();
					$dates = [];
					foreach ($products as $Product) {
						if (!in_array($Product->print_deadine, $dates)) {
							$dates[] = $Product->print_deadine;
						}
					}

					echo implode("<br/>", $dates);
				 ?>
			</b></td>
		</tr>
		<tr>
			<td>Products: </td>
			<td><b>
				<?
					$products = $Offer->getProducts();
					$i = 0;
					foreach ($products as $Product) {
					echo ++$i, '. ', implode(' / ', [
						$Product->number,
						$Product->circulation . ' stk.',
						'B: ' . $Product->format_width,
						'H: ' . $Product->format_height,
						$Product->colours,
						$Product->material_weight,
						$Product->quality,
						$Product->completion
					]), '<br />';
				 } ?>
			</b></td>
		</tr>
		<tr>
			<td>Addresses: </td>
			<td><b>
				<?
					$products = $Offer->getProducts();
					$addresses = [];
					foreach ($products as $Product) {
						$Delivery = $Product->getDeliveryInfo();
						foreach ($Delivery as $_delivery) {
							if (array_key_exists($_delivery->getAddress(), $addresses)) {
								$addresses[$_delivery->getAddress()] += $_delivery->piece;
							} else {
								$addresses[$_delivery->getAddress()] = $_delivery->piece;
							}
						}
					}

					foreach ($addresses as $key => $value) {
						echo $key, ' ', $value , ' stk. <br />';
					}
				?>
			</b></td>
		</tr>
	</table>
<? } ?>

<hr />
<?=forms_admin_errors($errors);?>
<hr />
<form method="post" action="">
	<textarea class="span12" name="text" cols="400" rows="15"></textarea>
	<input type="submit" class="btn btn-success" />
</form>
