<div class="row">
	<div class="span12">
		<h3>System parameters</h3>
		<hr />
	</div>
</div>


<div class="row">
	<div class="span12">
		<?=forms_admin_success($success); ?>
		<form class="form-horizontal" method="post" action="/dashboard/parameters">

			<? foreach ($system_settings as $key => $value) { ?>
				<div class="control-group">
					<label class="control-label"><?=$key?>: </label>
					<div class="controls">
						<div class="input">
							<input value="<?=$value?>" name="<?=$key?>" class="span5" id="appendedInput" type="text">
						</div>
					</div>
				</div>
			<? } ?>
			<hr />
			<button class="btn btn-primary btn-success" type="submit"><i class="icon-hdd icon-white"></i> Save</button>
			<a class="btn btn-primary btn-danger" href="/users/index"><i class="icon-backward icon-white"></i> Back</a>
		</form>
	</div>
</div>
