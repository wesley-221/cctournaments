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

		<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
		<link href="http://propeller.in/components/select2/css/select2-bootstrap.css" rel="stylesheet" />
		<link href="http://propeller.in/components/select2/css/pmd-select2.css" rel="stylesheet" />

		<title>Userpanel - CustomAllOsu</title>
	</head>

	<body>
		<?php include_once("./resources/includes/banner.php"); ?>
		<?php include_once("./resources/includes/navbar.php"); ?>

		<div class="container">
			<?php if(!isset($_GET['user'])) { ?>
				<ol class="breadcrumb">
					<li><a href="./">Home</a></li>
					<li><a href="./admin">Adminpanel</a></li>
					<li class="active">Userpanel</li>
				</ol>

				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title">Users</h3>
					</div>

					<div class="panel-body fullscreen-panel">
						<table id="userTable" class="table pmd-table table-hover">
							<tbody>
								<?php
									$allUsers = $db -> fetch('SELECT userId, username, flag FROM users', null, true);

									foreach($allUsers as $user) {
										echo '<tr class="clickableRow" data-href="./admin/userpanel/' . $user['userId'] . '"><td width="30"><img src="./resources/flags/' . $user['flag'] . '.png" /></td><td>' . $user["username"] . ' (' . $user['userId'] . ')</td></tr>';
									}
								?>
							</tbody>
						</table>
					</div>
				</div>

			<?php
			} else {
				$curUser = $db -> fetch('SELECT userId, username, email, permissionId, registrationDate, osuProfile FROM users WHERE userId = ?', [$_GET['user']]);
				if($curUser) {
			?>

			<ol class="breadcrumb">
				<li><a href="./">Home</a></li>
				<li><a href="./admin">Adminpanel</a></li>
				<li><a href="./admin/userpanel">Userpanel</a></li>
				<li class="active"><?php echo $curUser["username"]; ?></li>
			</ol>

			<?php
				$iUserId = isset($_POST['userId']) ? $_POST['userId'] : '';
				$sUsername = isset($_POST['userName']) ? $_POST['userName'] : '';
				$sUserEmail = isset($_POST['userEmail']) ? $_POST['userEmail'] : '';
				$iUserPermission = isset($_POST['userPermission']) ? $_POST['userPermission'] : '';
				$sUserProfile = isset($_POST['userProfile']) ? $_POST['userProfile'] : '';

				if($_SERVER['REQUEST_METHOD'] == 'POST') {
					$sProfileRegex = '/^(https|http):\/\/osu.ppy.sh\/u\/\d+$/';
					$sFinalString = "";

					if(!User::validateUsername($sUsername)) {
						$sFinalString .= 'This username does not meet the requirements. A username needs to contain at least ' . Config::get('validation/namemin') . ' characters and a maximum of ' . Config::get('validation/namemax') . '. <br>';
					}

					if(strcmp($sUsername, $curUser['username'])) {
						$validUsername = $db -> fetch('SELECT * FROM users WHERE username = ?', [$sUsername]);

						if($validUsername) {
							$sFinalString .= "This username is already in use. Please use a different one. <br>";
						}
					}

					if(!filter_var($sUserEmail, FILTER_VALIDATE_EMAIL)) {
						$sFinalString .= 'The email you entered is invalid. <br>';
					}

					if(!preg_match($sProfileRegex, $sUserProfile)) {
						$sFinalString .= 'The osu! profile you entered is invalid. Correct format: <a href="https://osu.ppy.sh/u/2407265">https://osu.ppy.sh/u/2407265</a>. <br>';
					}

					if(strlen($sFinalString) > 0) {
						echo '<div class="alert alert-danger" role="alert"><b>Something went wrong!</b><br>' . $sFinalString . '</div>';
					}
					else {
						$db -> execute('UPDATE users SET username = ?, email = ?, permissionId = ?, osuProfile = ? WHERE userId = ?', [$sUsername, $sUserEmail, $iUserPermission, $sUserProfile, $curUser['userId']]);

						echo '<div class="alert alert-success">Updated the user <b>' . $curUser['username'] . '</b></div>';
					}
				}
			?>

			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title"><?php echo $curUser["username"]; ?></h3>
				</div>

				<div class="panel-body fullscreen-panel">
					<form action="./admin/userpanel/<?php echo $curUser['userId']; ?>" method="post">
						<div class="form-group pmd-textfield pmd-textfield-floating-label">
							<label for="userId" class="control-label">User id</label>
							<input type="text" name="userId" class="form-control" value="<?php echo (strlen($iUserId) > 0) ? $iUserId : $curUser['userId']; ?>" disabled/>
						</div>

						<div class="form-group pmd-textfield pmd-textfield-floating-label">
							<label for="userName" class="control-label">Username</label>
							<input type="text" name="userName" class="form-control" value="<?php echo (strlen($sUsername) > 0) ? $sUsername : $curUser['username']; ?>" />
						</div>

						<div class="form-group pmd-textfield pmd-textfield-floating-label">
							<label for="userEmail" class="control-label">Email</label>
							<input type="text" name="userEmail" class="form-control" value="<?php echo (strlen($sUserEmail) > 0) ? $sUserEmail : $curUser['email']; ?>" />
						</div>

						<div class="form-group pmd-textfield pmd-textfield-floating-label">
							<label for="userPermission" class="control-label">Permission</label>
							<select name="userPermission" class="select-simple form-control pmd-select2">
								<?php
									$allPermissions = $db -> fetch('SELECT * FROM permission', null, true);

									foreach($allPermissions as $permission) {
										if(strlen($iUserPermission) > 0) {
											if($permission['permissionId'] == $iUserPermission)
												$selected = 'selected';
											else
												$selected = '';
										}
										else
											$selected = ($permission['permissionId'] == $curUser['permissionId']) ? 'selected' : '';

										echo '<option value="' . $permission['permissionId'] . '" ' . $selected . '>' . $permission['permissionName'] . '</option>';
									}
								?>
							</select>
						</div>

						<div class="form-group pmd-textfield pmd-textfield-floating-label">
							<label for="userProfile" class="control-label">osu! Profile</label>
							<input type="text" name="userProfile" class="form-control" value="<?php echo $curUser['osuProfile']; ?>" />
						</div>

						<button type="submit" class="btn btn-success pull-right"><i class="fa fa-floppy-o"></i> Save</button>
					</form>
				</div>
			</div>

			<?php
				}
				else {
			?>

			<div class="alert alert-danger">This user was not found. </div>

			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Users</h3>
				</div>

				<div class="panel-body fullscreen-panel">
					<ul class="nav nav-pills nav-stacked">
						<?php
							$allUsers = $db -> fetch('SELECT userId, username, email, permissionId, registrationDate, osuProfile FROM users', null, true);

							foreach($allUsers as $user) {
								echo '<li role="presentation"><a href="./admin/userpanel/' . $user["userId"] . '">' . $user["username"] . '</a></li>';
							}
						?>
					</ul>
				</div>
			</div>

			<?php
				}
			} ?>
		</div>

		<?php include_once('./resources/includes/loginpopup.php'); ?>
	</body>

	<script src="js/jquery-3.2.0.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/propeller.min.js"></script>
	<script type="text/javascript" src="http://propeller.in/components/select2/js/select2.full.js"></script>
	<script type="text/javascript" src="http://propeller.in/components/select2/js/pmd-select2.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$(".select-simple").select2({
				theme: "bootstrap",
				minimumResultsForSearch: Infinity,
			});

			$(".clickableRow").click(function() {
				window.location = $(this).data("href");
			});
		});
	</script>
</html>
