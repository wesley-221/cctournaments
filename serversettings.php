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

	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		$brandName = isset($_POST['brandName']) ? $_POST['brandName'] : '';
		$twitterURL = isset($_POST['twitterURL']) ? $_POST['twitterURL'] : '';
		$twitchURL = isset($_POST['twitchURL']) ? $_POST['twitchURL'] : '';
		$youtubeURL = isset($_POST['youtubeURL']) ? $_POST['youtubeURL'] : '';

		$db -> execute('UPDATE serverSettings SET brandName = ?, twitterURL = ?, twitchURL = ?, youtubeURL = ?', [$brandName, $twitterURL, $twitchURL, $youtubeURL]);
		Header('Location: ./admin/settings');
		return;
	}

	$curPage = 'admin';
?>

<!DOCTYPE html>
<html>
	<head>
		<?php include_once("./resources/includes/meta.php"); ?>
		<?php include_once("./resources/includes/link.php"); ?>

		<title>Serversettings - CustomAllOsu</title>
	</head>

	<body>
		<?php include_once("./resources/includes/banner.php"); ?>
		<?php include_once("./resources/includes/navbar.php"); ?>

		<div class="container">
			<ol class="breadcrumb">
				<li><a href="./">Home</a></li>
				<li><a href="./adminpanel.php">Adminpanel</a></li>
				<li class="active">Server settings</li>
			</ol>

			<form action="./admin/settings" method="post">
				<table class="table">
					<tbody>
						<tr>
							<td>
								<div class="form-group pmd-textfield pmd-textfield-floating-label">
									<label for="brandName" class="control-label pmd-input-group-label">Website brand name</label>
									<div class="input-group">
										<div class="input-group-addon"><i class="fa fa-link fa-2x"></i></div>
										<input type="text" name="brandName" class="form-control" value="<?php echo $serverSettings['brandName']; ?>" />
									</div>
								</div>
							</td>
						</tr>

						<tr>
							<td>
								<div class="form-group pmd-textfield pmd-textfield-floating-label">
									<label for="twitterURL" class="control-label pmd-input-group-label">Twitter</label>
									<div class="input-group">
										<div class="input-group-addon"><i class="fa fa-twitter fa-2x" style="color: #0084b4;"></i></div>
										<input type="text" name="twitterURL" class="form-control" value="<?php echo $serverSettings['twitterURL']; ?>" />
									</div>
								</div>
							</td>
						</tr>

						<tr>
							<td>
								<div class="form-group pmd-textfield pmd-textfield-floating-label">
									<label for="twitchURL" class="control-label pmd-input-group-label">Twitch</label>
									<div class="input-group">
										<div class="input-group-addon"><i class="fa fa-twitch fa-2x" style="color: #6441a5;"></i></div>
										<input type="text" name="twitchURL" class="form-control" value="<?php echo $serverSettings['twitchURL']; ?>" />
									</div>
								</div>
							</td>
						</tr>

						<tr>
							<td>
								<div class="form-group pmd-textfield pmd-textfield-floating-label">
									<label for="youtubeURL" class="control-label pmd-input-group-label">Youtube</label>
									<div class="input-group">
										<div class="input-group-addon"><i class="fa fa-youtube fa-2x" style="color: #bb0000;"></i></div>
										<input type="text" name="youtubeURL" class="form-control" value="<?php echo $serverSettings['youtubeURL']; ?>" />
									</div>
								</div>
							</td>
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
	<script src="js/propeller.min.js"></script>
</html>
