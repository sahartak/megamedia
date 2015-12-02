<!DOCTYPE html>
<html lang="en">
	<head>
	<meta charset="utf-8">
	<title><?php echo WEBSITE; ?> :: Client Universe</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">

	<!-- Le styles -->
	<link href="/themes/<?=THEME_NAME?>/css/bootstrap.min.css" rel="stylesheet">
	<style type="text/css">
		body {
		padding-top: 40px;
		padding-bottom: 40px;
		background-color: #f5f5f5;
		}

		.form-signin {
		max-width: 300px;
		padding: 19px 29px 29px;
		margin: 0 auto 20px;
		background-color: #fff;
		border: 1px solid #e5e5e5;
		-webkit-border-radius: 5px;
			 -moz-border-radius: 5px;
				border-radius: 5px;
		-webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
			 -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
				box-shadow: 0 1px 2px rgba(0,0,0,.05);
		}
		.form-signin .form-signin-heading,
		.form-signin .checkbox {
		margin-bottom: 10px;
		}
		.form-signin input[type="text"],
		.form-signin input[type="password"] {
		font-size: 16px;
		height: auto;
		margin-bottom: 15px;
		padding: 7px 9px;
		}

	</style>
	<link href="/themes/<?=THEME_NAME?>/css/bootstrap-responsive.min.css" rel="stylesheet">

	<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
		<script src="../assets/js/html5shiv.js"></script>
	<![endif]-->

	<link rel="icon" type="image/jpg" href="http://<?=HOST?>/themes/<?=THEME_NAME?>/img/MM_logo_bull.png">
	</head>

	<body>

	<div class="container">

		<form class="form-signin" method="post" action="/users/login">
		<h2 class="form-signin-heading">Please sign in</h2>
		<?=forms_admin_errors($errors)?>
		<input type="text" class="input-block-level" placeholder="Username" name="USER_LOGIN_ID" value="<?=forms_post('USER_LOGIN_ID')?>">
		<input type="password" class="input-block-level" placeholder="Password" name="CURRENT_PASSWORD"  value="<?=forms_post('CURRENT_PASSWORD')?>">
		<input type="hidden" class="input-block-level" placeholder="REFERAL" name="REFERAL" value="<?=isset($_GET['referer'])?urldecode($_GET['referer']):forms_post('REFERAL')?>">
		<!-- <label class="checkbox">
			<input type="checkbox" value="1" name="REMEMBER_ME"> Remember me
		</label> -->
		<button class="btn btn-success" type="submit">Sign in</button>
		</form>

<!--		<div class="well">-->
<!--		Forgotten password: Call <b>--><?//=$system_settings['PASSWORD_PHONE']?><!--</b>-->
<!--		or e-mail <b>--><?//=$system_settings['PASSWORD_MAIL']?><!--</b>-->
<!--		</div>-->

	</div> <!-- /container -->
	</body>
</html>
