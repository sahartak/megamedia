<?php
$store_list = db_query_to_array("SELECT * FROM `stores`");
?>
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
	<!--    <link rel="stylesheet" href="/themes/--><?//=THEME_NAME?><!--/css/bootstrap3.css">-->

	<link rel="stylesheet" href="/themes/<?=THEME_NAME?>/css/custom.css">
	<link rel="stylesheet" href="/themes/<?=THEME_NAME?>/css/docs.css">
	<style>
		#top_menu_nav li.active > a, #top_menu_nav li > a:hover {
			background-color: #a00;
		}
	</style>
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
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".navbar-inverse-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			<a class="brand" href="#">Title</a>
			<div class="nav-collapse collapse navbar-inverse-collapse">
				<ul class="nav" id="top_menu_nav">
					<li id="menu_dashboard"><a href="/">Dashboard</a></li>
					<li class="dropdown" id="menu_stores">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Stores <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="/store">Add new Store</a></li>
							<li class="divider"></li>
						<?php foreach($store_list as $store_info):?>
							<li><a href="/store/edit/<?=$store_info['id']?>"> <?= $store_info['name'] ?></a></li>
						<?php endforeach?>
						</ul>
					</li>
					<li id="menu_orders"><a href="/test/orders">Orders</a></li>
					<li><a href="/users/logout">Logout</a></li>
				</ul>
			</div><!-- /.nav-collapse -->
		</div>
	</div><!-- /navbar-inner -->
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
	<script src="/themes/<?=THEME_NAME?>/js/custom.js"></script>
	<script src="/themes/<?=THEME_NAME?>/js/BigDecimal-all-last.min.js"></script>

	<link rel="stylesheet" href="/themes/<?=THEME_NAME?>/css/colorbox.css" />
	<script src="/themes/<?=THEME_NAME?>/js/jquery.colorbox-min.js"></script>
	<script>
		jQuery('a.gallery').colorbox({rel:'gal'});
	</script>
</body>
</html>
