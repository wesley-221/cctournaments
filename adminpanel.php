<?php
	require_once 'core/init.php';

	$db = new Db(Config::get('mysql/host'), Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));
	$db -> setFetchMode(PDO::FETCH_ASSOC);
	$userData = User::authenticate(isset($_COOKIE[Config::get('cookie/cookie_name')]) ? $_COOKIE[Config::get('cookie/cookie_name')] : null);
	$serverSettings = $db -> fetch('SELECT * FROM serverSettings');

	if($userData['loggedin'] != 1 || $userData['permissionId'] <= 2) {
		Header('Location: ./');
		return;
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<?php include_once("./resources/includes/meta.php"); ?>
		<?php include_once("./resources/includes/link.php"); ?>

		<title>Adminpanel - CustomAllOsu</title>
	</head>

	<body>
		<?php include_once("./resources/includes/banner.php"); ?>
		<?php include_once("./resources/includes/navbar.php"); ?>

		<div class="container">
			<ol class="breadcrumb">
				<li><a href="./">Home</a></li>
				<li class="active">Adminpanel</li>
			</ol>

			<div class="row">
				<div class="col-xs-6">
					<a href="./admin/userpanel" class="thumbnail h4" align="center">
						<h1><i class="material-icons pmd-md">person</i></h1>
						<p>Userpanel</p>
					</a>
				</div>

				<div class="col-xs-6">
					<a href="./admin/settings" class="thumbnail h4" align="center">
						<h1><i class="material-icons pmd-md">settings</i></h1>
						<p>Server settings</p>
					</a>
				</div>
			</div>

			<div class="row">
				<div class="col-xs-6">
					<a href="./admin/news" class="thumbnail h4" align="center">
						<h1><i class="fa fa-newspaper-o"></i></h1>
						<p>Newspanel</p>
					</a>
				</div>

				<div class="col-xs-6">
					<a href="./admin/tournaments" class="thumbnail h4" align="center">
						<h1><i class="fa fa-trophy" aria-hidden="true"></i></h1>
						<p>Tournamentpanel</p>
					</a>
				</div>
			</div>

			<div class="row">
				<div class="col-xs-6">
					<a href="./admin/reports" class="thumbnail h4" align="center">
						<h1><i class="material-icons pmd-md">report</i></h1>
						<p>Reports</p>
					</a>
				</div>
			</div>
		</div>

		<?php include_once('./resources/includes/loginpopup.php'); ?>
	</body>

	<script src="js/jquery-3.2.0.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/propeller.min.js"></script>
</html>
