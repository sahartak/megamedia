<ul class="nav nav-tabs">
<?php
$banners_count = $posters_count = $rollups_count = 0;
if(isset($campaign_orders[1])):
	$banners_count = count($campaign_orders[1]);
	for($i=1; $i<=$banners_count; $i++):?>
		<li <?php if($i===1) echo 'class="active"'?>><a data-toggle="tab" href="#banner_<?=$i?>" >Banner <?=$i?></a></li>
<?php endfor; ?>
<?php else:?>
	<li class="active"><a data-toggle="tab" href="#banner_1">Banner 1</a></li>
<?php endif;?>
	<li><button class="btn btn-success btn-small" id="add_banner">Add banner</button></li>

<?php
if(isset($campaign_orders[2])):
	$posters_count = count($campaign_orders[2]);
	for($i=1; $i<=$posters_count; $i++):?>
		<li><a data-toggle="tab" href="#poster_<?=$i?>" >Poster <?=$i?></a></li>
	<?php endfor; ?>
<?php else:?>
	<li><a data-toggle="tab" href="#poster_1">Poster 1</a></li>
<?php endif;?>
	<li><button class="btn btn-success btn-small" id="add_poster">Add Poster</button></li>

<?php
if(isset($campaign_orders[3])):
	$rollups_count = count($campaign_orders[3]);
	for($i=1; $i<=$rollups_count; $i++):?>
		<li><a data-toggle="tab" href="#rollups_<?=$i?>" >Roll ups <?=$i?></a></li>
	<?php endfor; ?>
<?php else:?>
	<li><a data-toggle="tab" href="#rollups_1">Roll ups 1</a></li>
<?php endif;?>
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
	<?php if(!$banners_count):?>
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
	<?php else: $i=1; foreach($campaign_orders[1] as $b_key => $banner):?>
		<div id="banner_<?=$i?>" class="tab-pane<?if($i===1) echo' active';?>">
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
						<td>
							<input type="number" name="banners[<?=$i?>][<?=$store['id']?>][]" value="<?if(isset($banner[$store['id']])) echo $banner[$store['id']]['type_1']?>" class="span1 col1" min="0">
						</td>
						<td>
							<input type="number" name="banners[<?=$i?>][<?=$store['id']?>][]" value="<?if(isset($banner[$store['id']])) echo $banner[$store['id']]['type_2']?>"  class="span1 col2"  min="0">
						</td>
						<td>
							<input type="number" name="banners[<?=$i?>][<?=$store['id']?>][]" value="<?if(isset($banner[$store['id']])) echo $banner[$store['id']]['type_3']?>" class="span1 col3" min="0">
						</td>
						<td>
							<input type="number" name="banners[<?=$i?>][<?=$store['id']?>][]" value="<?if(isset($banner[$store['id']])) echo $banner[$store['id']]['type_4']?>" class="span1 col4" min="0">
						</td>
						<td>
							<?php
							$store_methods = &$stores_methods[$store['id']];
							if(count($store_methods)>1):
								?>
								<select name="banners[<?=$i?>][<?=$store['id']?>][]">
									<?php foreach($store_methods as $method):?>
										<option value="<?=$method['id']?>" <?if(isset($banner[$store['id']]) && $method['id']==$banner[$store['id']]['ophaeng_id']) echo 'selected';?>>
											<?=$method['name']?>
										</option>
									<?php endforeach;?>
								</select>
							<?php
							else: echo $store_methods[0]['name'];?>
								<input type="hidden" name="banners[<?=$i?>][<?=$store['id']?>][]" value="<?=$store_methods[0]['id']?>">
							<?php endif;?>
						</td>
						<td>
							<select name="banners[<?=$i?>][<?=$store['id']?>][]">
								<option value="1">PVC Frontlight</option>
								<option value="2" <?if(isset($banner[$store['id']]) && 2==$banner[$store['id']]['material_id']) echo 'selected';?>>PVC Mesh</option>
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
	<?php $i++; endforeach; endif;?>
	<?php if(!$posters_count):?>
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
	<?php else: $i=1; foreach($campaign_orders[2] as $p_key => $poster):?>
		<div id="poster_<?=$i?>" class="tab-pane ">
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
						<td>
							<input type="number" name="posters[<?=$i?>][<?=$store['id']?>][]" value="<?if(isset($poster[$store['id']])) echo $poster[$store['id']]['type_1']?>" class="span1 col1" min="0">
						</td>
						<td>
							<input type="number" name="posters[<?=$i?>][<?=$store['id']?>][]" value="<?if(isset($poster[$store['id']])) echo $poster[$store['id']]['type_2']?>" class="span1 col2"  min="0">
						</td>
						<td>
							<input type="number" name="posters[<?=$i?>][<?=$store['id']?>][]" value="<?if(isset($poster[$store['id']])) echo $poster[$store['id']]['type_3']?>" class="span1 col3" min="0">
						</td>
						<td>
							<input type="number" name="posters[<?=$i?>][<?=$store['id']?>][]" value="<?if(isset($poster[$store['id']])) echo $poster[$store['id']]['type_4']?>" class="span1 col4" min="0">
						</td>
						<td>
							<select name="posters[<?=$i?>][<?=$store['id']?>][]">
								<option value="3">Blueback</option>
								<option value="4" <?if(isset($poster[$store['id']]) && 4==$poster[$store['id']]['material_id']) echo 'selected';?>>Citylight</option>
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
	<?php $i++; endforeach; endif;?>
	<?php if(!$rollups_count):?>
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
	<?php else: $i=1; foreach($campaign_orders[3] as $r_key => $roll_up):?>
		<div id="rollups_<?=$i?>" class="tab-pane ">
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
						<td>
							<input type="number" name="rollups[<?=$i?>][<?=$store['id']?>][]" value="<?if(isset($roll_up[$store['id']])) echo $roll_up[$store['id']]['type_1']?>" class="span1 col1" min="0">
						</td>
						<td>
							<input type="number" name="rollups[<?=$i?>][<?=$store['id']?>][]" value="<?if(isset($roll_up[$store['id']])) echo $roll_up[$store['id']]['type_2']?>" class="span1 col2"  min="0">
						</td>
						<td>
							<input type="number" name="rollups[<?=$i?>][<?=$store['id']?>][]" value="<?if(isset($roll_up[$store['id']])) echo $roll_up[$store['id']]['type_3']?>" class="span1 col3" min="0">
						</td>
						<td>
							<input type="number" name="rollups[<?=$i?>][<?=$store['id']?>][]" value="<?if(isset($roll_up[$store['id']])) echo $roll_up[$store['id']]['type_4']?>" class="span1 col4" min="0">
						</td>
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
		<?php $i++; endforeach; endif;?>
	</div>
	<p class="pull-right"> <button class="btn btn-success">Continue</button> <a class="btn btn-info" href="/">Back</a></p>
</form>
</div>
<input type="hidden" id="banners_count" value="<?=$banners_count?>">
<input type="hidden" id="posters_count" value="<?=$posters_count?>">
<input type="hidden" id="rollups_count" value="<?=$rollups_count?>">
<script>
	$('#menu_dashboard').addClass('active');
</script>