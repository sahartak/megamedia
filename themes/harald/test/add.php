<ul class="nav nav-tabs">
	<li class="active"><a data-toggle="tab" href="#banner_1">Banner 1</a></li>
	<li><button class="btn btn-success btn-small" id="add_banner">Add banner</button></li>
	<li><a data-toggle="tab" href="#poster_1" >Poster 1</a></li>
	<li><button class="btn btn-success btn-small" id="add_poster">Add Poster</button></li>
	<li><a data-toggle="tab" href="#rollups_1" >Roll ups 1</a></li>
	<li><button class="btn btn-success btn-small" id="add_rollups">Add Roll ups</button></li>
</ul>

<div id="banner_template" class="hidden">
	<p><button type="button" class="btn btn-danger del_tab" data-type="banner">Delete Tab</button></p>
	<table class="table table-bordered table-striped add_campaign_table">
		<tr>
			<th>Store</th>
			<th>A390x300</th>
			<th>A100x100</th>
			<th>A300x290</th>
			<th>A580x450</th>
			<th>Ophaeng</th>
			<th>Material</th>
		</tr>
		<?php foreach($stores as $store):?>
			<tr>
				<td><?=$store['name'].' ('.$store['city'].')';?></td>
				<td><input type="number" name="[<?=$store['id']?>][]" class="span1 col1" min="0"></td>
				<td><input type="number" name="[<?=$store['id']?>][]"  class="span1 col2"  min="0"></td>
				<td><input type="number" name="[<?=$store['id']?>][]"  class="span1 col3" min="0"></td>
				<td><input type="number" name="[<?=$store['id']?>][]"  class="span1 col4" min="0"></td>
				<td>
					<?php
					$store_methods = &$stores_methods[$store['id']];
					if(count($store_methods)>1):
						?>
						<select name="[<?=$store['id']?>][]">
							<?php foreach($store_methods as $method):?>
								<option value="<?=$method['id']?>"><?=$method['name']?></option>
							<?php endforeach;?>
						</select>
					<?php
					else: echo $store_methods[0]['name'];?>
						<input type="hidden" name="[<?=$store['id']?>][]" value="<?=$store_methods[0]['id']?>">
					<?php endif;?>
				</td>
				<td>
					<select name="[<?=$store['id']?>][]">
						<option value="1">PVC Frontlight</option>
						<option value="2">PVC Mesh</option>
					</select>
				</td>
			</tr>
		<?php endforeach; ?>
		<tr>
			<td>Total</td>
			<td><span class="total_col1">0</span></td>
			<td><span class="total_col2">0</span></td>
			<td><span class="total_col3">0</span></td>
			<td><span class="total_col4">0</span></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
	</table>
</div>

<div id="poster_template" class="hidden">
	<p><button type="button" class="btn btn-danger del_tab" data-type="poster">Delete Tab</button></p>
	<table class="table table-bordered table-striped add_campaign_table">
		<tr>
			<th>Store</th>
			<th>Poster 1 <br /> 69x99</th>
			<th>Poster 2 <br /> 69x99</th>
			<th>Poster 3 <br /> 69x99</th>
			<th>Poster 4 <br /> 69x99</th>
			<th>Material</th>
		</tr>
		<?php foreach($stores as $store):?>
			<tr>
				<td><?=$store['name'].' ('.$store['city'].')';?></td>
				<td><input type="number" name="[<?=$store['id']?>][]" class="span1 col1" min="0"></td>
				<td><input type="number" name="[<?=$store['id']?>][]"  class="span1 col2"  min="0"></td>
				<td><input type="number" name="[<?=$store['id']?>][]"  class="span1 col3" min="0"></td>
				<td><input type="number" name="[<?=$store['id']?>][]"  class="span1 col4" min="0"></td>
				<td>
					<select name="[<?=$store['id']?>][]">
						<option value="3">Blueback</option>
						<option value="4">Citylight</option>
					</select>
				</td>
			</tr>
		<?php endforeach; ?>
		<tr>
			<td>Total</td>
			<td><span class="total_col1">0</span></td>
			<td><span class="total_col2">0</span></td>
			<td><span class="total_col3">0</span></td>
			<td><span class="total_col4">0</span></td>
			<td></td>
			<td></td>
		</tr>
	</table>
</div>

<div id="rollups_template" class="hidden">
	<p><button type="button" class="btn btn-danger del_tab" data-type="rollups">Delete Tab</button></p>
	<table class="table table-bordered table-striped add_campaign_table">
		<tr>
			<th>Store</th>
			<th>Roll up 1 <br /> 85x200</th>
			<th>Roll up 2 <br /> 85x200</th>
			<th>Roll up 3 <br /> 85x200</th>
			<th>Roll up 4 <br /> 85x200</th>
		</tr>
		<?php foreach($stores as $store):?>
			<tr>
				<td><?=$store['name'].' ('.$store['city'].')';?></td>
				<td><input type="number" name="[<?=$store['id']?>][]" class="span1 col1" min="0"></td>
				<td><input type="number" name="[<?=$store['id']?>][]"  class="span1 col2"  min="0"></td>
				<td><input type="number" name="[<?=$store['id']?>][]"  class="span1 col3" min="0"></td>
				<td><input type="number" name="[<?=$store['id']?>][]"  class="span1 col4" min="0"></td>
			</tr>
		<?php endforeach; ?>
		<tr>
			<td>Total</td>
			<td><span class="total_col1">0</span></td>
			<td><span class="total_col2">0</span></td>
			<td><span class="total_col3">0</span></td>
			<td><span class="total_col4">0</span></td>
		</tr>
	</table>
</div>

<form method="post" action="">
	<div class="tab-content">
		<input type="text" class="week_input" name="week_number" placeholder="Week number" value="<?php if(isset($week_number)) echo $week_number;?>" required="true" />
		<div id="banner_1" class="tab-pane active ">
			<table class="table table-bordered table-striped add_campaign_table">
				<tr>
					<th>Store</th>
					<th>A390x300</th>
					<th>A100x100</th>
					<th>A300x290</th>
					<th>A580x450</th>
					<th>Ophaeng</th>
					<th>Material</th>
				</tr>
				<?php foreach($stores as $store):?>
				<tr>
					<td><?=$store['name'].' ('.$store['city'].')';?></td>
					<td><input type="number" name="banners[1][<?=$store['id']?>][]" class="span1 col1" min="0"></td>
					<td><input type="number" name="banners[1][<?=$store['id']?>][]"  class="span1 col2"  min="0"></td>
					<td><input type="number" name="banners[1][<?=$store['id']?>][]"  class="span1 col3" min="0"></td>
					<td><input type="number" name="banners[1][<?=$store['id']?>][]"  class="span1 col4" min="0"></td>
					<td>
				<?php
					$store_methods = &$stores_methods[$store['id']];
					if(count($store_methods)>1):
				?>
						<select name="banners[1][<?=$store['id']?>][]">
						<?php foreach($store_methods as $method):?>
							<option value="<?=$method['id']?>"><?=$method['name']?></option>
						<?php endforeach;?>
						</select>
				<?php
					else: echo $store_methods[0]['name'];?>
						<input type="hidden" name="banners[1][<?=$store['id']?>][]" value="<?=$store_methods[0]['id']?>">
				<?php endif;?>
					</td>
					<td>
						<select name="banners[1][<?=$store['id']?>][]">
							<option value="1">PVC Frontlight</option>
							<option value="2">PVC Mesh</option>
						</select>
					</td>
				</tr>
				<?php endforeach; ?>
				<tr>
					<td>Total</td>
					<td><span class="total_col1">0</span></td>
					<td><span class="total_col2">0</span></td>
					<td><span class="total_col3">0</span></td>
					<td><span class="total_col4">0</span></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
			</table>

		</div>

		<div id="poster_1" class="tab-pane ">
			<table class="table table-bordered table-striped add_campaign_table">
				<tr>
					<th>Store</th>
					<th>Poster 1 <br /> 69x99</th>
					<th>Poster 2 <br /> 69x99</th>
					<th>Poster 3 <br /> 69x99</th>
					<th>Poster 4 <br /> 69x99</th>
					<th>Material</th>
				</tr>
				<?php foreach($stores as $store):?>
					<tr>
						<td><?=$store['name'].' ('.$store['city'].')';?></td>
						<td><input type="number" name="posters[1][<?=$store['id']?>][]" class="span1 col1" min="0"></td>
						<td><input type="number" name="posters[1][<?=$store['id']?>][]"  class="span1 col2"  min="0"></td>
						<td><input type="number" name="posters[1][<?=$store['id']?>][]"  class="span1 col3" min="0"></td>
						<td><input type="number" name="posters[1][<?=$store['id']?>][]"  class="span1 col4" min="0"></td>
						<td>
							<select name="posters[1][<?=$store['id']?>][]">
								<option value="3">Blueback</option>
								<option value="4">Citylight</option>
							</select>
						</td>
					</tr>
				<?php endforeach; ?>
				<tr>
					<td>Total</td>
					<td><span class="total_col1">0</span></td>
					<td><span class="total_col2">0</span></td>
					<td><span class="total_col3">0</span></td>
					<td><span class="total_col4">0</span></td>
					<td></td>
					<td></td>
				</tr>
			</table>
		</div>

		<div id="rollups_1" class="tab-pane ">
			<table class="table table-bordered table-striped add_campaign_table">
				<tr>
					<th>Store</th>
					<th>Roll up 1 <br /> 85x200</th>
					<th>Roll up 2 <br /> 85x200</th>
					<th>Roll up 3 <br /> 85x200</th>
					<th>Roll up 4 <br /> 85x200</th>
				</tr>
				<?php foreach($stores as $store):?>
					<tr>
						<td><?=$store['name'].' ('.$store['city'].')';?></td>
						<td><input type="number" name="rollups[1][<?=$store['id']?>][]" class="span1 col1" min="0"></td>
						<td><input type="number" name="rollups[1][<?=$store['id']?>][]"  class="span1 col2"  min="0"></td>
						<td><input type="number" name="rollups[1][<?=$store['id']?>][]"  class="span1 col3" min="0"></td>
						<td><input type="number" name="rollups[1][<?=$store['id']?>][]"  class="span1 col4" min="0"></td>
					</tr>
				<?php endforeach; ?>
				<tr>
					<td>Total</td>
					<td><span class="total_col1">0</span></td>
					<td><span class="total_col2">0</span></td>
					<td><span class="total_col3">0</span></td>
					<td><span class="total_col4">0</span></td>
				</tr>
			</table>
		</div>

	</div>
	<p class="pull-right"> <button class="btn btn-success">Continue</button> <a class="btn btn-info" href="/">Back</a></p>
</form>
</div>
<script>
	$('#menu_dashboard').addClass('active');
</script>