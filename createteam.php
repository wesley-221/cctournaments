<?php
	require_once 'core/init.php';

	$db = new Db(Config::get('mysql/host'), Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));
	$db -> setFetchMode(PDO::FETCH_ASSOC);
	$userData = User::authenticate(isset($_COOKIE[Config::get('cookie/cookie_name')]) ? $_COOKIE[Config::get('cookie/cookie_name')] : null);
	$serverSettings = $db -> fetch('SELECT * FROM serverSettings');

	$sTeamName = isset($_POST['teamName']) ? $_POST['teamName'] : '';
	$sTeamMainMode = isset($_POST['teamMainMode']) ? $_POST['teamMainMode'] : '';
	$sTeamRecruiting = isset($_POST['teamRecruiting']) ? $_POST['teamRecruiting'] : '';
	$sTeamRequirements = isset($_POST['teamRequirements']) ? $_POST['teamRequirements'] : '';
	$arrAvatar = isset($_FILES['avatar']) ? $_FILES['avatar'] : '';

	$curPage = 'teams';
?>

<!DOCTYPE html>
<html>
	<head>
		<?php include_once("./resources/includes/meta.php"); ?>
		<?php include_once("./resources/includes/link.php"); ?>

		<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
		<link href="http://propeller.in/components/select2/css/select2-bootstrap.css" rel="stylesheet" />
		<link href="http://propeller.in/components/select2/css/pmd-select2.css" rel="stylesheet" />

		<title>Create a team - CustomAllOsu</title>
	</head>

	<body>
		<?php include_once("./resources/includes/banner.php"); ?>
		<?php include_once("./resources/includes/navbar.php"); ?>

		<aside id="sidebar" class="sidebarshow">
			<nav class="list-group">
				<a href="./teams" class="list-group-item">All teams</a>
				<a href="./manageteams" class="list-group-item">Manage your teams</a>
				<a href="./createteam" class="list-group-item active">Create a team</a>
			</nav>
		</aside>

		<div class="content">
			<?php
				if($_SERVER['REQUEST_METHOD'] == 'POST') {
					print_r($_FILES['avatar']);

					$sFinalString = '';

					$phpFileUploadErrors = array(
						0 => 'There is no error, the file uploaded with success.',
						1 => 'The uploaded file you tried to upload exceeds the maximum filesize allowed.',
						2 => 'The uploaded file you tried to upload exceeds the maximum filesize allowed.',
						3 => 'The uploaded file was only partially uploaded.',
						4 => 'No file was uploaded.',
						6 => 'Missing a temporary folder.',
						7 => 'Failed to write file to disk.',
						8 => 'An unknown error has occured.',
					);

					if(strlen($sTeamName) < 3 || strlen($sTeamName) >= 23) {

					}

					if($sTeamMainMode == "osu!Standard" || $sTeamMainMode == "osu!Taiko" || $sTeamMainMode == "osu!Catch" || $sTeamMainMode == "osu!Mania"){} else {

					}

					if($sTeamRecruiting == "yes" || $sTeamRecruiting == "no"){} else {

					}

					if(strlen($sTeamRequirements) < 3 || strlen($sTeamRequirements) > 1000) {
						$sFinalString .= 'The requirements has to contain at least 3 characters and a maximum of 1000. <br />';
					}

					if($arrAvatar['error'] !== UPLOAD_ERR_OK) {
						$sFinalString .= 'An error was found while uploading this image: ' . $phpFileUploadErrors[$arrAvatar['error']] . '<br />';
					}
					else {
						$avaInfo = pathinfo(basename($arrAvatar['name']));

						if($arrAvatar['size'] > 1024000) {
							$sFinalString .= 'The file you tried to upload exceeds the maximum filesize allowed. Maximum filesize: 1024kb. <br />';
						}

						if(!in_array($avaInfo['extension'], Config::get('config/picture_extension'))) {
							$allowedext = implode(', ', Config::get('config/picture_extension'));
							$sFinalString .= 'The file you tried to upload has an invalid extension. Allowed extensions: ' . $allowedext . '. <br />';
						}

						if(!Functions::check_file_uploaded_name($arrAvatar['name'])) {
							$sFinalString .= 'The name of the file contains illegal characters. Use english only characters in the name. <br />';
						}

						if(strlen($arrAvatar['name']) > 255) {
							$sFinalString .= 'The length of the file name is too long. Change the file name to something shorter. <br />';
						}
					}

					if(strlen($sFinalString) > 0) {
						echo '<div class="alert alert-danger"><b>Something went wrong!</b><br />' . $sFinalString . '</div>';
					}
					else {
						if($arrAvatar['error'] === UPLOAD_ERR_OK) {
							$curDate = date('Y-m-d h:i:s');

							$db -> execute('INSERT INTO teams(teamName, teamOwnerUserId, teamMainMode, teamDateCreated, teamRecruiting, teamRequirements, teamRankingScore) VALUES(?, ?, ?, ?, ?, ?, ?)',
																[$sTeamName, $userData['userId'], $sTeamMainMode, $curDate, $sTeamRecruiting, $sTeamRequirements, 0]);
							$lastId = $db -> fetch('SELECT last_insert_id()');
							$db -> execute('INSERT INTO teammembers VALUES(?, ?, ?)', [$lastId['last_insert_id()'], $userData['userId'], $curDate]);

							foreach(Config::get('config/picture_extension') as $ext) {
								if(file_exists('./resources/avatars/teams/' . $lastId['last_insert_id()'] . '.' . $ext)) {
									unlink('./resources/avatars/teams/' . $lastId['last_insert_id()'] . '.' . $ext);
								}
							}

							move_uploaded_file($arrAvatar['tmp_name'], './resources/avatars/teams/' . $lastId['last_insert_id()'] . '.' . $avaInfo['extension']);

							Header('Location: ./teams/' . $lastId['last_insert_id()']);
							return;
						}
					}
				}
			?>

			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Create a team</h3>
				</div>

				<div class="panel-body">
					<form action="./createteam" method="post" enctype="multipart/form-data">
						<div class="row">
							<div class="col-xs-12">
								<div class="form-group pmd-textfield pmd-textfield-floating-label">
								   <label for="teamName" class="control-label">Team name</label>
								   <input type="text" id="teamName" name="teamName" class="form-control" value="<?php echo $sTeamName; ?>" />
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-xs-12">
								<div class="form-group pmd-textfield pmd-textfield-floating-label">
								   <label for="teamMainMode" class="control-label">Team main mode</label>
								   <select id="teamMainMode" name="teamMainMode" class="select-simple form-control pmd-select2">
									   <option value="osu!Standard">osu!Standard</option>
									   <option value="osu!Taiko">osu!Taiko</option>
									   <option value="osu!Catch">osu!Catch</option>
									   <option value="osu!Mania">osu!Mania</option>
								   </select>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-xs-12">
								<div class="form-group pmd-textfield pmd-textfield-floating-label">
								   <label for="teamRecruiting" class="control-label">Recruiting</label>
								   <select id="teamRecruiting" name="teamRecruiting" class="select-simple form-control pmd-select2">
									   <option value="1">Yes</option>
									   <option value="0">No</option>
								   </select>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-xs-12">
								<div class="form-group pmd-textfield">
								   <label for="teamRequirements" class="control-label">Requirements to join the team</label>
								   <textarea class="form-control" name="teamRequirements" style="height: 200px;"><?php echo $sTeamRequirements; ?></textarea>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-xs-12">
								<label for="avatar" class="textareaLabel">Avatar</label>
								<input type="file" name="avatar" />
							</div>
						</div>

						<div class="row">
							<div class="col-xs-12">
								<button class="btn btn-success pmd-btn-raised pull-right">Create</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>

		<?php include_once('./resources/includes/loginpopup.php'); ?>
	</body>

	<script src="js/jquery-3.2.0.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/propeller.min.js"></script>
	<script type="text/javascript" src="http://propeller.in/components/select2/js/select2.full.js"></script>
	<script type="text/javascript" src="http://propeller.in/components/select2/js/pmd-select2.js"></script>
	<script>
		$(".select-simple").select2({
			theme: "bootstrap",
			minimumResultsForSearch: Infinity,
		});
	</script>
</html>
