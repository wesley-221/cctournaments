<?php
	require_once 'core/init.php';

	$db = new Db(Config::get('mysql/host'), Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));
	$db -> setFetchMode(PDO::FETCH_ASSOC);
	$userData = User::authenticate(isset($_COOKIE[Config::get('cookie/cookie_name')]) ? $_COOKIE[Config::get('cookie/cookie_name')] : null);
	$serverSettings = $db -> fetch('SELECT * FROM serverSettings');
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Settings - CustomAllOsu</title>

		<meta name="viewport" content="minimum-scale=1.0, width=device-width, maximum-scale=1.0, user-scalable=no" />
		<link href="css/bootstrap.min.css" rel="stylesheet" />
		<link href="resources/fontawesome/css/font-awesome.min.css" rel="stylesheet" />
		<link href="css/style.css" rel="stylesheet" />
	</head>

	<body>
		<?php include_once("./resources/includes/banner.php"); ?>
		<?php include_once("./resources/includes/navbar.php"); ?>

		<div class="container">
			<table class="table table-striped">
				<tbody>
					<tr>
						<td>Option</td><td>Slider</td>
					</tr>
				</tbody>
			</table>
		</div>

		<?php include_once('./resources/includes/loginpopup.php'); ?>
	</body>

	<script src="js/jquery-3.2.0.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</html>
