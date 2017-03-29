<?php
	require_once 'core/init.php';

	$db = new Db(Config::get('mysql/host'), Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));
	$db -> setFetchMode(PDO::FETCH_ASSOC);
	$userData = User::authenticate(isset($_COOKIE[Config::get('cookie/cookie_name')]) ? $_COOKIE[Config::get('cookie/cookie_name')] : null);
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>About - CustomAllOsu</title>

		<meta name="viewport" content="minimum-scale=1.0, width=device-width, maximum-scale=1.0, user-scalable=no" />
		<link href="css/bootstrap.min.css" rel="stylesheet" />
		<link href="resources/fontawesome/css/font-awesome.min.css" rel="stylesheet" />
		<link href="css/style.css" rel="stylesheet" />
	</head>

	<body>
		<div class="banner">
			<div class="bannerContent pull-right">
				<a href="https://twitter.com/CustomAllOsu"><i class="fa fa-twitter fa-5x" style="color: #0084b4;" aria-hidden="true"></i></a>
				<a href="https://www.twitch.tv/CustomAllOsu"><i class="fa fa-twitch fa-5x" style="color: #6441a5;" aria-hidden="true"></i></a>
				<a href="https://www.youtube.com/channel/UC3-XrF1BxnWiBrdM27sZ89g"><i class="fa fa-youtube fa-5x" style="color: #bb0000;" aria-hidden="true"></i></a>
			</div>
		</div>

		<?php include_once("./resources/includes/navbar.php"); ?>

		<div class="container">

		</div>

		<?php include_once('./resources/includes/loginpopup.php'); ?>
	</body>

	<script src="js/jquery-3.2.0.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</html>
