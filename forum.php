<?php
	require_once 'core/init.php';

	$db = new Db(Config::get('mysql/host'), Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));
	$db -> setFetchMode(PDO::FETCH_ASSOC);
	$userData = User::authenticate(isset($_COOKIE[Config::get('cookie/cookie_name')]) ? $_COOKIE[Config::get('cookie/cookie_name')] : null);
	$serverSettings = $db -> fetch('SELECT * FROM serverSettings');

	$curPage = 'forum';
?>

<!DOCTYPE html>
<html>
	<head>
		<?php include_once("./resources/includes/meta.php"); ?>
		<?php include_once("./resources/includes/link.php"); ?>

		<title>Forum - CustomAllOsu</title>
	</head>

	<body>
		<?php include_once("./resources/includes/banner.php"); ?>
		<?php include_once("./resources/includes/navbar.php"); ?>

		<div class="container">


			<div class="category-x pmd-z-depth">
				<div class="forumHeader">Title</div>

				<div class="categoryBlock">
					<div class="block">
						<div class="row">
							<div class="col-xs-1">
								picca
							</div>

							<div class="col-xs-8">
								<div class="blockTitle">blockTitle</div>
								<div class="blockDescription">blockDescription</div>
							</div>

							<div class="col-xs-3">
								text
							</div>
						</div>
					</div>

					<div style="margin-bottom: 5px;"></div>

					<div class="block">
						<div class="row">
							<div class="col-xs-1">
								picca
							</div>

							<div class="col-xs-8">
								<div class="blockTitle">blockTitle</div>
								<div class="blockDescription">blockDescription</div>
							</div>

							<div class="col-xs-3">
								text
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php include_once('./resources/includes/loginpopup.php'); ?>
	</body>

	<script src="js/jquery-3.2.0.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/propeller.min.js"></script>
</html>
