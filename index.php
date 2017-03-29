<?php
	require_once 'core/init.php';

	$db = new Db(Config::get('mysql/host'), Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));
	$db -> setFetchMode(PDO::FETCH_ASSOC);

	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		$loginUsername = isset($_POST['loginUsername']) ? $_POST['loginUsername'] : '';
		$loginPassword = isset($_POST['loginPassword']) ? $_POST['loginPassword'] : '';
		$hashedPassword = User::hashPassword($loginPassword);

		$validUsername = $db -> fetch('SELECT userid, username, password, salt FROM users WHERE username = ?', [$loginUsername]);

		if($validUsername) {
			if(!strcmp($validUsername["password"], $hashedPassword)) {
				$qCookieExist = $db -> fetch('SELECT * FROM cookies WHERE userid = ?', [$validUsername["userid"]]);

				if($qCookieExist)
					$db -> execute('DELETE FROM cookies WHERE userid = ?', [$validUsername["userid"]]);

				$sCookieValue = User::GenerateCookie($loginUsername);
				setcookie(Config::get('cookie/cookie_name'), $sCookieValue, strtotime("+1 month"));

				$db -> execute('INSERT into cookies VALUES(?, ?)', [$validUsername["userid"], $sCookieValue]);
				Header('Location: ./');
			}
			else {
				$showError = array("show" => 2);
			}
		}
		else {
			$showError = array("show" => 1, "user" => $loginUsername);
		}
	}

	$userData = User::authenticate(isset($_COOKIE[Config::get('cookie/cookie_name')]) ? $_COOKIE[Config::get('cookie/cookie_name')] : null);
	$serverSettings = $db -> fetch('SELECT * FROM serverSettings');
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Index - CustomAllOsu</title>

		<link rel="shortcut icon" href="./resources/media/favicon.png">

		<meta name="viewport" content="minimum-scale=1.0, width=device-width, maximum-scale=1.0, user-scalable=no" />
		<link href="css/bootstrap.min.css" rel="stylesheet" />
		<link href="resources/fontawesome/css/font-awesome.min.css" rel="stylesheet" />
		<link href="css/style.css" rel="stylesheet" />
	</head>

	<body>
		<?php include_once("./resources/includes/banner.php"); ?>
		<?php include_once("./resources/includes/navbar.php"); ?>

		<div class="container-fluid">
			<?php
				if(isset($showError)) {
					if($showError["show"] == 1) {
						echo '<div class="alert alert-danger">The user "' . $showError["user"] . '" was not found. Please try again.</div>';
					}
					else if($showError["show"] == 2){
						echo '<div class="alert alert-danger">The password you entered is invalid. Please try again.</div>';
					}
				}
			?>

			<div class="row">
				<div class="col-lg-8 col-sm-6">
					<div class="panel panel-primary news">
						<div class="panel-heading">
							<h3 class="panel-title">News <span class="pull-right"><a href="#" class="showAllNews">Click here to view all news articles</span></span></h3>
						</div>

						<div class="panel-body">
							<div class="newsarticle">
								<div class="newstitle"><a href="#"><b>Dummy title - 03/27/2017</b></a></div>

								<div class="newscontent">
									This is a description of a dummy article. Want to know more? Don't look any further, the future is here.
									This is a description of a dummy article. Want to know more? Don't look any further, the future is here.
									This is a description of a dummy article. Want to know more? Don't look any further, the future is here.
									This is a description of a dummy article. Want to know more? Don't look any further, the future is here.
									This is a description of a dummy article. Want to know more? Don't look any further, the future is here.
									This is a description of a dummy article. Want to know more? Don't look any further, the future is here.
								</div>
							</div>

							<div class="extraSpacing3"></div>

							<div class="newsarticle">
								<div class="newstitle"><a href="#"><b>Dummy title - 03/27/2017</b></a></div>

								<div class="newscontent">
									This is a description of a dummy article. Want to know more? Don't look any further, the future is here.
									This is a description of a dummy article. Want to know more? Don't look any further, the future is here.
									This is a description of a dummy article. Want to know more? Don't look any further, the future is here.
									This is a description of a dummy article. Want to know more? Don't look any further, the future is here.
									This is a description of a dummy article. Want to know more? Don't look any further, the future is here.
									This is a description of a dummy article. Want to know more? Don't look any further, the future is here.
								</div>
							</div>

							<div class="extraSpacing3"></div>

							<div class="newsarticle">
								<div class="newstitle"><a href="#"><b>Dummy title - 03/27/2017</b></a></div>

								<div class="newscontent">
									This is a description of a dummy article. Want to know more? Don't look any further, the future is here.
									This is a description of a dummy article. Want to know more? Don't look any further, the future is here.
									This is a description of a dummy article. Want to know more? Don't look any further, the future is here.
									This is a description of a dummy article. Want to know more? Don't look any further, the future is here.
									This is a description of a dummy article. Want to know more? Don't look any further, the future is here.
									This is a description of a dummy article. Want to know more? Don't look any further, the future is here.
								</div>
							</div>

							<div class="extraSpacing3"></div>

							<div class="newsarticle">
								<div class="newstitle"><a href="#"><b>Dummy title - 03/27/2017</b></a></div>

								<div class="newscontent">
									This is a description of a dummy article. Want to know more? Don't look any further, the future is here.
									This is a description of a dummy article. Want to know more? Don't look any further, the future is here.
									This is a description of a dummy article. Want to know more? Don't look any further, the future is here.
									This is a description of a dummy article. Want to know more? Don't look any further, the future is here.
									This is a description of a dummy article. Want to know more? Don't look any further, the future is here.
									This is a description of a dummy article. Want to know more? Don't look any further, the future is here.
								</div>
							</div>

							<div class="extraSpacing3"></div>

							<div class="newsarticle">
								<div class="newstitle"><a href="#"><b>Dummy title - 03/27/2017</b></a></div>

								<div class="newscontent">
									This is a description of a dummy article. Want to know more? Don't look any further, the future is here.
									This is a description of a dummy article. Want to know more? Don't look any further, the future is here.
									This is a description of a dummy article. Want to know more? Don't look any further, the future is here.
									This is a description of a dummy article. Want to know more? Don't look any further, the future is here.
									This is a description of a dummy article. Want to know more? Don't look any further, the future is here.
									This is a description of a dummy article. Want to know more? Don't look any further, the future is here.
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-lg-4 col-sm-6">
					<div class="row">
						<div class="col-sm-12">
							<div class="panel panel-primary twitter">
								<div class="panel-heading">
									<h3 class="panel-title">Twitter <span class="pull-right"><a href="https://twitter.com/CustomAllOsu" class="twitter-follow-button" data-show-count="false">Follow @CustomAllOsu</a></span></h3>
								</div>

								<div class="panel-body fixed-panel">
									<a class="twitter-timeline" href="https://twitter.com/CustomAllOsu" data-tweet-limit="3">Tweets by CustomAllOsu</a>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-sm-12">
							<div class="panel panel-primary twitch">
								<div class="panel-heading">
									<h3 class="panel-title">Twitch</h3>
								</div>

								<div class="panel-body fixed-panel">
									<div id="twitchDiv"></div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-sm-12">
							<div class="panel panel-primary youtube">
								<div class="panel-heading">
									<h3 class="panel-title">Youtube</h3>
								</div>

								<div class="panel-body fixed-panel">
									<div id="youtubeDiv">Soon™</div>
									<!-- <iframe width="100%" src="http://www.youtube.com/embed?max-results=1&controls=0&showinfo=0&rel=0&listType=user_uploads&list=3-XrF1BxnWiBrdM27sZ89g" frameborder="0" allowfullscreen></iframe>  -->
								</div>
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
	<script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
	<script src= "https://player.twitch.tv/js/embed/v1.js"></script>
	<script>
		var player = new Twitch.Player("twitchDiv", {channel: "customallosu", width: "100%"});
	</script>
</html>
