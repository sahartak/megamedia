<?php
if(isset($_SESSION['edit_store_msg'])){
	echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button>',$_SESSION['edit_store_msg'],'</div>';
	unset($_SESSION['edit_store_msg']);
}
?>
<div class="row"><h1>Edit a store</h1></div>
<?php if(isset($error_msg)) echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">×</button>',$error_msg,'</div>'?>
<form method="post">
	<div class="row">
		<div class="span6">
			<h3>Address</h3>
			<div class="row">
				<div class="span2"><label for="shop_name">Shop name and No:</label></div>
				<div class="span3"><input id="shop_name" value="<?=$store['name']?>" name="shop_name" type="text" class="form-control" required="true" /></div>
			</div><br />
			<div class="row">
				<div class="span2"><label for="address">Street name:</label></div>
				<div class="span3"><input id="address" value="<?=$store['street_name']?>" name="street_name" type="text" class="form-control" required="true" /></div>
			</div><br />
			<div class="row">
				<div class="span2"><label for="city">City:</label></div>
				<div class="span3"><input id="city" value="<?=$store['city']?>" name="city" type="text" class="form-control" required="true" /></div>
			</div><br />
			<div class="row">
				<div class="span2"><label for="postal_code">Postal code:</label></div>
				<div class="span3"><input id="postal_code" value="<?=$store['postal_code']?>" name="postal_code" type="text" class="form-control" required="true" /></div>
			</div><br />
			<div class="row">
				<div class="span2"><label for="country">Country:</label></div>
				<div class="span3">
					<select id="country" name="country" class="form-control" required="true" >
						<?php $countries = get_countries();
						foreach($countries as $country):?>
							<option <?php if($country == $store['country']) echo 'selected';?>><?=$country?></option>
						<?php endforeach;?>
					</select>
				</div>
			</div>
		</div>

		<div class="span6">
			<h3>Map</h3>
			<div id="map_canvas" style="height:270px;"></div><br>
			<input id="latitude" name="lat" value="<?=$store['lat']?>" type="hidden">
			<input id="longitude" name="long" value="<?=$store['long']?>" type="hidden">
		</div>
    </div>
	<div class="row">
		<div class="span6">
			<h3>Contact</h3>
			<div class="row">
				<div class="span2"><label for="contact_name">Contact name:</label></div>
				<div class="span3"><input id="contact_name" value="<?=$store['contact_name']?>" name="contact_name" type="text" class="form-control" required="true" /></div>
			</div><br />
			<div class="row">
				<div class="span2"><label for="phone_number">Phone number:</label></div>
				<div class="span3"><input id="phone_number" value="<?=$store['phone_number']?>" name="phone_number" type="text" class="form-control" required="true" /></div>
			</div><br />
			<div class="row">
				<div class="span2"><label for="email_address">Email address:</label></div>
				<div class="span3"><input id="email_address" value="<?=$store['email_address']?>" name="email_address" type="email" class="form-control" required="true" /></div>
			</div><br />
		</div>
		<div class="span6">
			<h3>Configuration</h3>
			<div class="row">
				<div class="span2"><label for="contact_name">Hanging method:</label></div>
				<div class="span3">
					<?php foreach($hanging_methods as $hanging):?>
						<p>
							<input type="checkbox" name="hanging_methods[]" id="hanging_<?=$hanging['id']?>" value="<?=$hanging['id']?>"
								<?php if(in_array($hanging['id'], $store_hanging_methods)) echo 'checked'?>>
							<label class="inline" for="hanging_<?=$hanging['id']?>"><?=$hanging['name']?></label>
						</p>
					<?php endforeach;?>
				</div>
			</div><br />
		</div>
	</div>
	<div class="row">
		<div class="span6">
			<p><input type="submit" class="btn btn-success" /></p>
		</div>
	</div>

</form>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&region=DK"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script type="text/javascript">
	$('#menu_stores').addClass('active');
	var geocoder;
	var map;
	var marker;

	function initialize(){

		var latlng = new google.maps.LatLng(55.6760968, 12.568337100000008);
		var options = {
			zoom: 15,
			center: latlng,
			mapTypeId: google.maps.MapTypeId.MAP
		};

		map = new google.maps.Map(document.getElementById("map_canvas"), options);
		geocoder = new google.maps.Geocoder();
		marker = new google.maps.Marker({
			map: map,
			draggable: true
		});

	}

	$(document).ready(function() {

		initialize();
		var location = new google.maps.LatLng(<?=$store['lat']?>, <?=$store['long']?>);
		marker.setPosition(location);
		map.setCenter(location);

		$(function() {
			$("#address").autocomplete({

				source: function(request, response) {
					geocoder.geocode( {'address': request.term}, function(results, status) {
						response($.map(results, function(item) {
							return {
								label:  item.formatted_address,
								value: item.formatted_address,
								latitude: item.geometry.location.lat(),
								longitude: item.geometry.location.lng()
							}
						}));
					})
				},

				select: function(event, ui) {
					$("#latitude").val(ui.item.latitude);
					$("#longitude").val(ui.item.longitude);
					var location = new google.maps.LatLng(ui.item.latitude, ui.item.longitude);
					marker.setPosition(location);
					map.setCenter(location);
				}
			});
		});

		google.maps.event.addListener(marker, 'drag', function() {
			geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					if (results[0]) {
						$('#address').val(results[0].formatted_address);
						$('#latitude').val(marker.getPosition().lat());
						$('#longitude').val(marker.getPosition().lng());
					}
				}
			});
		});

	});

</script>