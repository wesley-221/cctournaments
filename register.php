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
		<?php include_once("./resources/includes/meta.php"); ?>
		<?php include_once("./resources/includes/link.php"); ?>

		<title>Register - CustomAllOsu</title>
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
						$sFinalString .= "This username is already in use. Please use a different one. <br>";
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
						$sUserSalt = User::createSalt(Functions::generate_uniqueID(50));
						$sUserPass = User::hashPassword($sPassword);
						$regDate = date('Y-m-d');

						if(!empty($_SERVER['HTTP_CLIENT_IP']))
							$ip = $_SERVER['HTTP_CLIENT_IP'];
						else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
							$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
						else
							$ip = $_SERVER['REMOTE_ADDR'];

						$countryCode = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $ip))['geoplugin_countryCode'];
						$sMainMode = "osu!Catch";

						if($countryCode == "") $countryCode = "NL";

						$db -> execute('INSERT INTO users(username, password, salt, userMainMode, flag, email, registrationDate, osuProfile) VALUES(?, ?, ?, ?, ?, ?, ?, ?)',
															[$sUsername, $sUserPass, $sUserSalt, $sMainMode, $countryCode, $sEmail, $regDate, $sProfile]);
						Header('Location: ./');
					}
				}
			?>


			<form action="./register" method="post">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title">Enter your account details here</h3>
					</div>

					<div class="panel-body">
						<div class="row">
							<div class="col-xs-12">
								<div class="form-group pmd-textfield pmd-textfield-floating-label">
									<label for="registerUsername" class="control-label pmd-input-group-label">Enter username here</label>
									<div class="input-group">
										<div class="input-group-addon"><i class="material-icons pmd-sm">perm_identity</i></div>
										<input id="registerUsername" name="registerUsername" type="text" class="form-control" value="<?php echo $sUsername; ?>" />
									</div>
								</div>
							</div>
						</div>

						<div class="extraSpacing3"></div>

						<div class="row">
							<div class="col-xs-12">
								<div class="form-group pmd-textfield pmd-textfield-floating-label">
									<label for="registerPassword" class="control-label pmd-input-group-label">Enter password here</label>
									<div class="input-group">
										<div class="input-group-addon"><i class="material-icons pmd-sm">lock_outline</i></div>
										<input id="registerPassword" name="registerPassword" type="password" class="form-control" />
									</div>
								</div>
							</div>
						</div>

						<div class="extraSpacing3"></div>

						<div class="row">
							<div class="col-xs-12">
								<div class="form-group pmd-textfield pmd-textfield-floating-label">
									<label for="registerEmail" class="control-label pmd-input-group-label">Enter email here</label>
									<div class="input-group">
										<div class="input-group-addon"><i class="material-icons pmd-sm">mail_outline</i></div>
										<input id="registerEmail" name="registerEmail" type="email" class="form-control" value="<?php echo $sEmail; ?>" />
									</div>
								</div>
							</div>
						</div>

						<div class="extraSpacing3"></div>

						<div class="row">
							<div class="col-xs-12">
								<div class="form-group pmd-textfield pmd-textfield-floating-label">
									<label for="registerEmail" class="control-label pmd-input-group-label">osu! profile</label>
									<div class="input-group">
										<div class="input-group-addon"><i class="material-icons pmd-sm">videogame_asset</i></div>
										<input id="registerProfile" name="registerProfile" type="text" class="form-control" value="<?php echo $sProfile; ?>" />
									</div>
								</div>
							</div>

							<!-- <div class="col-xs-3"><label for="registerProfile">osu! profile:</label></div>
							<div class="col-xs-9"><input id="registerProfile" name="registerProfile" type="text" class="form-control" placeholder="https://osu.ppy.sh/u/2407265" value="<?php echo $sProfile; ?>" /></div> -->
						</div>
					</div>

					<div class="panel-footer">
						<button type="submit" class="btn btn-primary pmd-btn-raised">Register</button>
					</div>
				</div>
			</form>
		</div>

		<?php include_once('./resources/includes/loginpopup.php'); ?>
	</body>

	<script src="js/jquery-3.2.0.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/propeller.min.js"></script>
</html>
