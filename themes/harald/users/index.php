<div class="row" style="margin-left: 5px;">
	<h3>Customers
			<span style="float:right">
				<a class="btn btn-success" href="/users/profile"><i class="icon-plus-sign icon-white"></i> Add New</a>
			</span>
	</h3>
</div>

<div class="row">
	<div class="span12">
		<?=forms_admin_success($success); ?>
		<?=forms_admin_errors($errors); ?>
	</div>
</div>

<div class="row" style="margin-left: 5px;">
	<table class="table table-bordered">
		<tr class="warning">
			<td><b>Id</b></td>
			<td><b>Client</b></td>
			<td><b>Actions</b></td>
		</tr><? foreach($customers as $_customer){ ?>
			<tr>
				<td><?=$_customer['PARTY_ID']?></td>
				<td><?=stripslashes($_customer['FIRST_NAME'])?> <?=stripslashes($_customer['LAST_NAME'])?></td>
				<td class="span3" style="text-align: right;">
					<a href="/products/prices?PARTY_ID=<?=$_customer['PARTY_ID']?>" class="btn btn-small btn-info"><b>$</b></a>
					<a href="/users/index?VIEW=<?=$_customer['PARTY_ID']?>" class="btn btn-small btn-info"><i class="icon-shopping-cart icon-white"></i></a>
					<a href="/users/index?PROFILE=<?=$_customer['PARTY_ID']?>" class="btn btn-small btn-info"><i class="icon-user icon-white"></i></a>
					<a data-type="confirm" class="btn btn-small btn-danger delete-button" href="/users/index?DELETE=<?=$_customer['PARTY_ID']?>"><i class="icon-trash icon-white"></i></a>
				</td>
			</tr>
		<? } ?>
	</table>
</div>
