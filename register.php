<?php
	require_once 'core/init.php';

	$db = new Db(Config::get('mysql/host'), Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));
	$db -> setFetchMode(PDO::FETCH_ASSOC);
	$userData = User::authenticate(isset($_COOKIE[Config::get('cookie/cookie_name')]) ? $_COOKIE[Config::get('cookie/cookie_name')] : null);
	$serverSettings = $db -> fetch('SELECT * FROM serverSettings');
	
	if($userData["loggedin"] == 1)
		Header('Location: ./');
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Register - CustomAllOsu</title>

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
			<?php
				$sUsername = isset($_POST['registerUsername']) ? $_POST['registerUsername'] : '';
				$sPassword = isset($_POST['registerPassword']) ? $_POST['registerPassword'] : '';
				$sEmail    = isset($_POST['registerEmail'])    ? $_POST['registerEmail']    : '';
				$sProfile  = isset($_POST['registerProfile'])  ? $_POST['registerProfile']  : '';

				if($_SERVER['REQUEST_METHOD'] == 'POST') {
					$sProfileRegex = '/^(https|http):\/\/osu.ppy.sh\/u\/\d+$/';
					$sFinalString = "";

					if(!User::validateUsername($sUsername)) {
						$sFinalString .= 'This username does not meet the requirements. A username needs to contain at least ' . Config::get('validation/namemin') . ' characters and a maximum of ' . Config::get('validation/namemax') . '. <br>';
					}

					$validUsername = $db -> fetch('SELECT * FROM users WHERE username = ?', [$sUsername]);

					if($validUsername) {
						// print_r($validUsername);
						$sFinalString .= "This username is already in use. Please use a different one.";
					}

					if(!User::validatePassword($sPassword)) {
						$sFinalString .= 'This password does not meet the requirements. A password needs to contain at least ' . Config::get('validation/passwordmin') . ' characters and a maximum of ' . Config::get('validation/passwordmax') . '. <br>';
					}

					if(!filter_var($sEmail, FILTER_VALIDATE_EMAIL)) {
						$sFinalString .= 'The email you entered is invalid. <br>';
					}

					if(!preg_match($sProfileRegex, $sProfile)) {
						$sFinalString .= 'The osu! profile you entered is invalid. Correct format: <a href="https://osu.ppy.sh/u/2407265">https://osu.ppy.sh/u/2407265</a>. <br>';
					}

					if(strlen($sFinalString) > 0) {
						echo '<div class="alert alert-danger" role="alert"><b>Something went wrong!</b><br>' . $sFinalString . '</div>';
					}
					else {
						$sUserHash = Functions::generate_uniqueID(50);
						$sUserPass = User::hashPassword($sPassword);
						$regDate = date('Y-m-d');

						$db -> execute('INSERT INTO users(username, password, salt, email, registrationDate, osuProfile) VALUES(?, ?, ?, ?, ?, ?)', [$sUsername, $sUserPass, $sUserHash, $sEmail, $regDate, $sProfile]);

						Header('Location: ./');
					}
				}
			?>


			<form action="./register.php" method="post">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title">Enter your account details here</h3>
					</div>

					<div class="panel-body">
						<div class="row">
							<div class="col-xs-3"><label for="registerUsername">Username:</label></div>
							<div class="col-xs-9"><input id="registerUsername" name="registerUsername" type="text" class="form-control" placeholder="Enter username here" autofocus value="<?php echo $sUsername; ?>" /></div>
						</div>

						<div class="extraSpacing3"></div>

						<div class="row">
							<div class="col-xs-3"><label for="registerPassword">Password:</label></div>
							<div class="col-xs-9"><input id="registerPassword" name="registerPassword" type="password" class="form-control" placeholder="Enter password here" /></div>
						</div>

						<div class="extraSpacing3"></div>

						<div class="row">
							<div class="col-xs-3"><label for="registerEmail">Email:</label></div>
							<div class="col-xs-9"><input id="registerEmail" name="registerEmail" type="email" class="form-control" placeholder="Enter email here" value="<?php echo $sEmail; ?>" /></div>
						</div>

						<div class="extraSpacing3"></div>

						<div class="row">
							<div class="col-xs-3"><label for="registerProfile">osu! profile:</label></div>
							<div class="col-xs-9"><input id="registerProfile" name="registerProfile" type="text" class="form-control" placeholder="https://osu.ppy.sh/u/2407265" value="<?php echo $sProfile; ?>" /></div>
						</div>
					</div>

					<div class="panel-footer">
						<button type="submit" class="btn btn-primary">Register</button>
					</div>
				</div>
			</form>
		</div>

		<?php include_once('./resources/includes/loginpopup.php'); ?>
	</body>

	<script src="js/jquery-3.2.0.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</html>
