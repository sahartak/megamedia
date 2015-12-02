<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title><?=WEBSITE?> :: Customer Universe</title>
	<meta content="width=device-width, initial-scale=1.0" name="viewport">
	<meta content="" name="description">
	<meta content="" name="author">

	<!-- Le styles -->
	<link rel="stylesheet" href="/themes/<?=THEME_NAME?>/css/bootstrap.min.css">
	<link rel="stylesheet" href="/themes/<?=THEME_NAME?>/css/bootstrap-responsive.min.css">
	<link rel="stylesheet" href="/themes/<?=THEME_NAME?>/css/bootstrap-responsive-480px.min.css">


	<link rel="stylesheet" href="/themes/<?=THEME_NAME?>/css/custom.css">
	<link rel="stylesheet" href="/themes/<?=THEME_NAME?>/css/docs.css">

	<script src="/themes/<?=THEME_NAME?>/js/jquery.js"></script>

	<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
		<script src="assets/js/html5shiv.js"></script>
	<![endif]-->

	<!-- Le fav and touch icons -->
	<link href="assets/ico/apple-touch-icon-144-precomposed.png" sizes="144x144" rel="apple-touch-icon-precomposed">
	<link href="assets/ico/apple-touch-icon-114-precomposed.png" sizes="114x114" rel="apple-touch-icon-precomposed">
	<link href="assets/ico/apple-touch-icon-72-precomposed.png" sizes="72x72" rel="apple-touch-icon-precomposed">
	<link href="assets/ico/apple-touch-icon-57-precomposed.png" rel="apple-touch-icon-precomposed">

	<link rel="icon" type="image/jpg" href="http://<?=HOST?>/themes/<?=THEME_NAME?>/img/MM_logo_bull.png">

</head>
<body>

	<div class="navbar navbar-inverse navbar-fixed-top">
		<div class="navbar-inner">
		<div class="container">
			<button data-target=".nav-collapse" data-toggle="collapse" class="btn btn-navbar" type="button">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			</button>
			<a href="./index.html" class="brand" style="color: #FFFFFF;"><img src="http://<?=HOST?>/themes/<?=THEME_NAME?>/img/MM_logo.png" style="height: 20px;" /></a>
			<div class="nav-collapse collapse">
			<ul class="nav">

				<? if( is_admin() ){ ?>
					<li class="dropdown"><a href="/users/index" class="dropdown-toggle">Customers</a></li>
					<li class="dropdown"><a href="/dashboard/parameters" class="dropdown-toggle">Parameters</a></li>
					<li class="dropdown"><a href="/products/prices" class="dropdown-toggle">Pricing</a></li>
					<li class="dropdown"><a href="/parse/parse" class="dropdown-toggle">Parse</a></li>
				<? } else { ?>
					<li class="dropdown"><a href="/dashboard/dashboard" class="dropdown-toggle">Product Overview</a></li>
					<li class="dropdown"><a href="/orders/list" class="dropdown-toggle">Orders</a></li>
				<? } ?>


				<? if( !is_admin() ){ ?>
					<li class="dropdown<?=($module=='index')?' active':''?>"><a href="/users/profile" class="dropdown-toggle">Profile</a></li>
				<? } ?>

				<li class=""><a href="/users/logout">Logout</a></li>
				<? if ($Cart->getProductsCount()) { ?>
				<li>
					<a href="/orders/cart">
					<span class="badge badge-info">
						<i class="icon-shopping-cart icon-white"></i>
						<?=$Cart->getProductsCount()?> Items&nbsp;&nbsp;&nbsp;&nbsp;<?=number_format($Cart->getTotal(), 2)?> <?=DEFAULT_CURRENCY?>
					</span>
					</a>
				</li>
				<? } ?>
			</ul>
			</div>
		</div>
		</div>
	</div>

	<? if( in_array($module, $full_screen_modules) &&  in_array($controller, $full_screen_controllers) ){  ?>
			<div style="padding: 20px;">
	<? }else{ ?>
			<div class="container" style="padding-top:20px;">
	<?
		}
		require_once THEME . $template . '.php';
	?>
	</div>

	<div class="container">
		<hr />
		<div class="pull-left">
			Yor are logged in as:
			<b>
				<?=$_SESSION['user']['FIRST_NAME'] . ' ' . $_SESSION['user']['LAST_NAME']?>
				<?=is_admin() ? '(Administrator)' : '' ?>
			</b><br />
			Last interaction: <?=date('d.m.Y H:i:s')?><br ?>
			MegaMedia ApS Customer Universe v 3.1.0
		</div>
		<div class="pull-right">
			Powered by <a href="http://petev.net" target="_blank">Hristo Petev</a>
		</div>
	</div>

	<script src="/themes/<?=THEME_NAME?>/js/bootstrap.min.js"></script>
	<script src="/themes/<?=THEME_NAME?>/js/bootstrap-tab.js"></script>
	<script src="/themes/<?=THEME_NAME?>/js/bootstrap-tooltip.js"></script>
	<script src="/themes/<?=THEME_NAME?>/js/common.js"></script>

	<script src="/themes/<?=THEME_NAME?>/js/BigDecimal-all-last.min.js"></script>

	<link rel="stylesheet" href="/themes/<?=THEME_NAME?>/css/colorbox.css" />
	<script src="/themes/<?=THEME_NAME?>/js/jquery.colorbox-min.js"></script>
	<script>
		jQuery('a.gallery').colorbox({rel:'gal'});
	</script>
</body>
</html>
