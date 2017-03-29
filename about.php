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
		<title>About - CustomAllOsu</title>

		<link rel="shortcut icon" href="./resources/media/favicon.png">

		<meta name="viewport" content="minimum-scale=1.0, width=device-width, maximum-scale=1.0, user-scalable=no" />
		<link href="css/bootstrap.min.css" rel="stylesheet" />
		<link href="resources/fontawesome/css/font-awesome.min.css" rel="stylesheet" />
		<link href="css/style.css" rel="stylesheet" />
	</head>

	<body>
		<?php include_once("./resources/includes/banner.php"); ?>
		<?php include_once("./resources/includes/navbar.php"); ?>

		<div class="container">
			<div class="row">
				<div class="col-xs-12">
					<div class="panel panel-primary news">
						<div class="panel-body">
							<p>
								Our previous tournaments in chronological order


								(The headers link to the tournament thread)
								The representative cup


								The representative cup was a tournament mainly done by Sartan, whereas Wesley entered the fray a few weeks after the initial matches. Even though this tournament was very badly organised (a total mess to be honest) it still inspired us to create CCTournaments and start hosting actual legitimate tournaments with a bit of quality to them. Even though we should've forgotten about this tournament as fast as possible, the moment of it was burned into the retina of our eyes and never left since.

								AxS 2015-2016


								AxS 2015-2016 stays in the history books as the most participated in community made osu!Catch tournament ever created. With a total of 151 players this was a very popular tournament for it's time and was lots better organized than the Representative cup (with actual map pickers, a stream etc.). Sadly all of the streaming footage of it was lost overtime because we didn't upload it to youtube, which we started doing with AxS 2016-2017 (more about that tournament coming soon). Even though this tournament, in our opinion, was a great success we kept on trying to improve our approach to tournaments afterwards, fixing as many bad quirks as possible.

								MaS
								MaS was an experimental tournament where you would sign up on your own and then be put into a randomized team. Even though the concept worked and we got enough sign-ups to proceed with the entire ordeal the organization was lacking a little bit due to unmotivated staff (cough Sartan playing the new WoW expansion) and lots of matches were cancelled because players didn't turn up, making the tournament somewhat messy. We finished the tournament all happy and well and the players were satisfied you could say, but thanks to the above mentioned things that went wrong with MaS we decided not to pursue it and leave it as it is, not making a second edition of it (unless there comes a very high player demand which we don't see happening).

								AxS 2016-2017


								AxS 2016-2017 is our most recent tournament and so far the most well organized one by a landslide. Even though we had less players as opposed to AxS 2015-2016, the matches were of higher caliber and smoother. With more match hosts, a consistent stream with vods being uploaded to youtube, very few occasions of frustrated players and an overall good atmosphere we can regard this tournament as our best one so far. This was also the trigger to start recruiting a permanent staff team (literally this post) and to ensue other ways of connecting with the community via CustomEverythingOsu (coming soon™).
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php include_once('./resources/includes/loginpopup.php'); ?>
	</body>

	<script src="js/jquery-3.2.0.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</html>
