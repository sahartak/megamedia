<form method="post" action="/users/profile" class="form-horizontal">
	<div class="row">
		<div class="span12">
			<h2>Customer profile
				<? if (isset($_SESSION['FAKE_LOGIN']) && $_SESSION['FAKE_LOGIN']) { ?>
					<span style="float:right">
						<a class="btn" href="/users/logout"><i class="icon-backward icon-gray"></i> To Customers List</a>
					</span>
				<? } ?>
			</h2>
			<hr />
		</div>
	</div>

	<div class="row">
		<div class="span12">
			<?=forms_admin_success($success); ?>
			<?=forms_admin_errors($errors); ?>
		</div>
	</div>

	<div class="row">
		<div class="span6">
			<!-- PERSONAL INFORMATION -->
			<fieldset>
				<legend>General</legend>
				<div class="control-group <?=(in_array('PARTY_ID', $error_fields)) ? ' error' : ''?>">
					<label class="control-label">ID: <b class="mandatory">*</b></label>
					<div class="controls">
						<input <?=!is_admin() ? 'disabled' : ''?> type="text" style="margin-bottom: 0;" value="<?=stripslashes(forms_post_or_data($customer, 'PARTY_ID'))?>" class="input-xlarge" name="PARTY_ID">
					</div>
				</div>

				<div class="control-group <?=(in_array('FIRST_NAME', $error_fields)) ? ' error' : ''?>">
					<label class="control-label">Contact First Name: <b class="mandatory">*</b></label>
					<div class="controls">
						<input type="text" style="margin-bottom: 0;" value="<?=stripslashes(forms_post_or_data($customer, 'FIRST_NAME'))?>" class="input-xlarge" name="FIRST_NAME">
					</div>
				</div>

				<div class="control-group <?=(in_array('LAST_NAME', $error_fields)) ? ' error' : ''?>">
					<label class="control-label">Contact Last Name: <b class="mandatory">*</b></label>
					<div class="controls">
						<input type="text" style="margin-bottom: 0;" value="<?=stripslashes(forms_post_or_data($customer, 'LAST_NAME'))?>" class="input-xlarge" name="LAST_NAME">
					</div>
				</div>

				<div class="control-group <?=(in_array('CONTACT_PHONE', $error_fields)) ? ' error' : ''?>">
					<label class="control-label">Contact Telephone: <b class="mandatory">*</b></label>
					<div class="controls">
						<input type="text" style="margin-bottom: 0;" value="<?=stripslashes(forms_post_or_data($contact_person, 'CONTACT_PHONE'))?>" class="input-xlarge" name="CONTACT_PHONE">
					</div>
				</div>

				<div class="control-group <?=(in_array('CONTACT_EMAIL', $error_fields)) ? ' error' : ''?>">
					<label class="control-label">Contact Email: <b class="mandatory">*</b></label>
					<div class="controls">
						<input type="text" style="margin-bottom: 0;" value="<?=stripslashes(forms_post_or_data($contact_person, 'CONTACT_EMAIL'))?>" class="input-xlarge" name="CONTACT_EMAIL">
					</div>
				</div>

				<legend>Login</legend>
				<div class="control-group <?=(in_array('USER_LOGIN_ID', $error_fields)) ? ' error' : ''?>">
					<label class="control-label">Username: <b class="mandatory">*</b></label>
					<div class="controls">
						<input type="text" style="margin-bottom: 0;" value="<?=forms_post_or_data($user_login, 'USER_LOGIN_ID')?>" class="input-xlarge" name="USER_LOGIN_ID">
					</div>
				</div>

				<div class="control-group <?=(in_array('CURRENT_PASSWORD', $error_fields)) ? ' error' : ''?>">
					<label class="control-label">Password: <b class="mandatory">*</b></label>
					<div class="controls">
						<input type="text" style="margin-bottom: 0;" value="<?=forms_post_or_data($user_login, 'CURRENT_PASSWORD')?>" class="input-xlarge" name="CURRENT_PASSWORD">
					</div>
				</div>

				<legend>MegaMedia Internal</legend>
				<div class="control-group <?=(in_array('AFFILIATE_NAME', $error_fields)) ? ' error' : ''?>">
					<label class="control-label">Handler: <b class="mandatory">*</b></label>
					<div class="controls">
						<input type="text" style="margin-bottom: 0;" value="<?=forms_post_or_data($affiliate, 'AFFILIATE_NAME')?>" class="input-xlarge" name="AFFILIATE_NAME">
					</div>
				</div>

				<div class="control-group <?=(in_array('AFFILIATE_DESCRIPTION', $error_fields)) ? ' error' : ''?>">
					<label class="control-label">Handler Telephone: <b class="mandatory">*</b></label>
					<div class="controls">
						<input type="text" style="margin-bottom: 0;" value="<?=forms_post_or_data($affiliate, 'AFFILIATE_DESCRIPTION')?>" class="input-xlarge" name="AFFILIATE_DESCRIPTION">
					</div>
				</div>

				<div class="control-group <?=(in_array('AFFILIATE_EMAIL', $error_fields)) ? ' error' : ''?>">
					<label class="control-label">Handler e-mail: <b class="mandatory">*</b></label>
					<div class="controls">
						<input type="text" style="margin-bottom: 0;" value="<?=forms_post_or_data($affiliate, 'AFFILIATE_EMAIL')?>" class="input-xlarge" name="AFFILIATE_EMAIL">
					</div>
				</div>

				<!-- CONTACT INFORMATION -->
			</div>
			<div class="span6">
				<legend>Address</legend>

				<div class="control-group <?=(in_array('NAME', $error_fields)) ? ' error' : ''?>">
					<label class="control-label">Company Name: <b class="mandatory">*</b></label>
					<div class="controls">
						<input type="text" style="margin-bottom: 0;" value="<?=forms_post_or_data($contact_attributes, 'NAME')?>" class="input-xlarge" name="NAME">
					</div>
				</div>

				<div class="control-group <?=(in_array('STREET', $error_fields)) ? ' error' : ''?>">
					<label class="control-label">Streetname, number: <b class="mandatory">*</b></label>
					<div class="controls">
						<input type="text" style="margin-bottom: 0;" value="<?=forms_post_or_data($contact_attributes, 'STREET')?>" class="input-xlarge" name="STREET">
					</div>
				</div>

				<div class="control-group <?=(in_array('POSTAL', $error_fields)) ? ' error' : ''?>">
					<label class="control-label">Postal number: <b class="mandatory">*</b></label>
					<div class="controls">
						<input type="text" style="margin-bottom: 0;" value="<?=forms_post_or_data($contact_attributes, 'POSTAL')?>" class="input-xlarge" name="POSTAL">
					</div>
				</div>

				<div class="control-group <?=(in_array('CITY', $error_fields)) ? ' error' : ''?>">
					<label class="control-label">City: <b class="mandatory">*</b></label>
					<div class="controls">
						<input type="text" style="margin-bottom: 0;" value="<?=forms_post_or_data($contact_attributes, 'CITY')?>" class="input-xlarge" name="CITY">
					</div>
				</div>

				<div class="control-group <?=(in_array('PHONE', $error_fields)) ? ' error' : ''?>">
					<label class="control-label">Telephone: <b class="mandatory">*</b></label>
					<div class="controls">
						<input type="text" style="margin-bottom: 0;" value="<?=forms_post_or_data($contact_attributes, 'PHONE')?>" class="input-xlarge" name="PHONE">
					</div>
				</div>

				<div class="control-group">
					<label class="control-label">Country:</label>
					<div class="controls">
						<input type="text" style="margin-bottom: 0;" value="<?=forms_post_or_data($contact_attributes, 'COUNTRY')?>" class="input-xlarge" name="COUNTRY">
					</div>
				</div>

				<legend>Orders</legend>
				<div class="control-group <?=(in_array('ORDER_EMAIL', $error_fields)) ? ' error' : ''?>">
					<label class="control-label">Order email: <b class="mandatory">*</b></label>
					<div class="controls">
						<input type="text" style="margin-bottom: 0;" value="<?=forms_post_or_data($order_contacts_attributes, 'ORDER_EMAIL')?>" class="input-xlarge" name="ORDER_EMAIL">
					</div>
				</div>

				<div class="control-group <?=(in_array('ORDER_FTP_ADDRESS', $error_fields)) ? ' error' : ''?>">
					<label class="control-label">FTP Address:</label>
					<div class="controls">
						<input type="text" style="margin-bottom: 0;" value="<?=forms_post_or_data($order_contacts_attributes, 'ORDER_FTP_ADDRESS')?>" class="input-xlarge" name="ORDER_FTP_ADDRESS">
					</div>
				</div>

				<div class="control-group <?=(in_array('ORDER_FTP_USER', $error_fields)) ? ' error' : ''?>">
					<label class="control-label">FTP Username:</label>
					<div class="controls">
						<input type="text" style="margin-bottom: 0;" value="<?=forms_post_or_data($order_contacts_attributes, 'ORDER_FTP_USER')?>" class="input-xlarge" name="ORDER_FTP_USER">
					</div>
				</div>

				<div class="control-group <?=(in_array('ORDER_FTP_PASS', $error_fields)) ? ' error' : ''?>">
					<label class="control-label">FTP Password:</label>
					<div class="controls">
						<input type="text" style="margin-bottom: 0;" value="<?=forms_post_or_data($order_contacts_attributes, 'ORDER_FTP_PASS')?>" class="input-xlarge" name="ORDER_FTP_PASS">
					</div>
				</div>

			</fieldset>
		</div>
	</div>

	<hr />

	<div class="control-group">
		<div class="controls">
			<button class="btn btn-primary" type="submit"><i class="icon-hdd icon-white"></i> Save</button>
		</div>
	</div>
</form>
