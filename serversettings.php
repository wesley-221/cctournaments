<?php
	require_once 'core/init.php';

	$db = new Db(Config::get('mysql/host'), Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));
	$db -> setFetchMode(PDO::FETCH_ASSOC);
	$userData = User::authenticate(isset($_COOKIE[Config::get('cookie/cookie_name')]) ? $_COOKIE[Config::get('cookie/cookie_name')] : null);
	$serverSettings = $db -> fetch('SELECT * FROM serverSettings');

	if($userData['loggedin'] != 1 && $userData['permissionId'] <= 2) {
		Header('Location: ./');
		return;
	}
	
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		$brandName = isset($_POST['brandName']) ? $_POST['brandName'] : '';
		$twitterURL = isset($_POST['twitterURL']) ? $_POST['twitterURL'] : '';
		$twitchURL = isset($_POST['twitchURL']) ? $_POST['twitchURL'] : '';
		$youtubeURL = isset($_POST['youtubeURL']) ? $_POST['youtubeURL'] : '';

		$db -> execute('UPDATE serverSettings SET brandName = ?, twitterURL = ?, twitchURL = ?, youtubeURL = ?', [$brandName, $twitterURL, $twitchURL, $youtubeURL]);
		Header('Location: ./serversettings.php');
		return;
	}
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
		<?php include_once("./resources/includes/banner.php"); ?>
		<?php include_once("./resources/includes/navbar.php"); ?>

		<div class="container">
			<form action="./serversettings.php" method="post">
				<table class="table table-striped">
					<tbody>
						<tr>
							<td>Website brand name</td>
							<td><input type="text" name="brandName" class="form-control" value="<?php echo $serverSettings['brandName']; ?>" /></td>
						</tr>

						<tr>
							<td>Twitter</td>
							<td><input type="text" name="twitterURL" class="form-control" value="<?php echo $serverSettings['twitterURL']; ?>" /></td>
						</tr>

						<tr>
							<td>Twitch</td>
							<td><input type="text" name="twitchURL" class="form-control" value="<?php echo $serverSettings['twitchURL']; ?>" /></td>
						</tr>

						<tr>
							<td>Youtube</td>
							<td><input type="text" name="youtubeURL" class="form-control" value="<?php echo $serverSettings['youtubeURL']; ?>" /></td>
						</tr>
					</tbody>

					<tfoot>
						<tr>
							<td colspan="2"><button type="submit" class="btn btn-success pull-right"><i class="fa fa-floppy-o"></i> Save</button></td>
						</tr>
					</tfoot>
				</table>
			</form>
		</div>

		<?php include_once('./resources/includes/loginpopup.php'); ?>
	</body>

	<script src="js/jquery-3.2.0.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</html>
