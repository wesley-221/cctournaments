<?php
	require_once 'core/init.php';

	$db = new Db(Config::get('mysql/host'), Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));
	$db -> setFetchMode(PDO::FETCH_ASSOC);
	$userData = User::authenticate(isset($_COOKIE[Config::get('cookie/cookie_name')]) ? $_COOKIE[Config::get('cookie/cookie_name')] : null);
	$serverSettings = $db -> fetch('SELECT * FROM serverSettings');

	if($userData['loggedin'] == 0) {
		Header('Location: ./teams');
		return;
	}

	$curPage = 'teams';
?>

<!DOCTYPE html>
<html>
	<head>
		<?php include_once("./resources/includes/meta.php"); ?>
		<?php include_once("./resources/includes/link.php"); ?>

		<title>Manage teams - CustomAllOsu</title>
	</head>

	<body>
		<?php include_once("./resources/includes/banner.php"); ?>
		<?php include_once("./resources/includes/navbar.php"); ?>

		<aside id="sidebar" class="sidebarshow">
			<nav class="list-group">
				<a href="./teams" class="list-group-item">All teams</a>
				<a href="./manageteams" class="list-group-item active">Manage your teams</a>
				<a href="./createteam" class="list-group-item">Create a team</a>
			</nav>
		</aside>

		<div class="content">
			<?php
				if(isset($_GET['t'])) {
					$curTeam = $db -> fetch('SELECT * FROM teams WHERE teamId = ?', [$_GET['t']]);

					if($curTeam) {
						$isOwnerOfTeam = 0;
						$isPartOfTeam = 0;

						if($userData['userId'] === $curTeam['teamOwnerUserId'])
							$isOwnerOfTeam = 1;

						$allMembers = $db -> fetch('SELECT teammembers.userId, teammembers.teamId, users.userName, users.flag FROM teammembers, users WHERE teammembers.userId = users.userId AND teammembers.teamId = ?', [$curTeam['teamId']], true);

						foreach($allMembers as $member) {
							if($member['userId'] == $userData['userId']) {
								$isPartOfTeam = 1;
								break;
							}
						}

						if($isPartOfTeam !== 0) {
							if($_SERVER['REQUEST_METHOD'] == 'POST') {
								$sFinalString = '';
								$sRequirements = isset($_POST['requirements']) ? $_POST['requirements'] : '';
								$avatar = isset($_FILES['avatar']) ? $_FILES['avatar'] : '';

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

								if($avatar['error'] !== UPLOAD_ERR_OK) {
									if($avatar['error'] !== UPLOAD_ERR_NO_FILE) {
										$sFinalString .= 'An error was found while uploading this image: ' . $phpFileUploadErrors[$avatar['error']] . '<br />';
									}
								}
								else {
									$avaInfo = pathinfo(basename($avatar['name']));

									if($avatar['size'] > 1024000) {
										$sFinalString .= 'The file you tried to upload exceeds the maximum filesize allowed. Maximum filesize: 88kb. <br />';
									}

									if(!in_array($avaInfo['extension'], Config::get('config/picture_extension'))) {
										$allowedext = implode(', ', Config::get('config/picture_extension'));
										$sFinalString .= 'The file you tried to upload has an invalid extension. Allowed extensions: ' . $allowedext . '. <br />';
									}

									if(!Functions::check_file_uploaded_name($avatar['name'])) {
										$sFinalString .= 'The name of the file contains illegal characters. Use english only characters in the name. <br />';
									}

									if(strlen($avatar['name']) > 255) {
										$sFinalString .= 'The length of the file name is too long. Change the file name to something shorter. <br />';
									}
								}

								if(strlen($sRequirements) < 3 || strlen($sRequirements) > 1000) {
									$sFinalString .= 'The requirements has to contain at least 3 characters and a maximum of 1000. <br />';
								}

								if(strlen($sFinalString) > 0) {
									echo '<div class="alert alert-danger"><b>Something went wrong!</b><br />' . $sFinalString . '</div>';
								}
								else {
									if($avatar['error'] === UPLOAD_ERR_OK) {
										foreach(Config::get('config/picture_extension') as $ext) {
											if(file_exists('./resources/avatars/teams/' . $curTeam['teamId'] . '.' . $ext)) {
												unlink('./resources/avatars/teams/' . $curTeam['teamId'] . '.' . $ext);
											}
										}

										move_uploaded_file($avatar['tmp_name'], './resources/avatars/teams/' . $curTeam['teamId'] . '.' . $avaInfo['extension']);
									}

									$db -> execute('UPDATE teams SET teamRequirements = ? WHERE teamId = ?', [$sRequirements, $curTeam['teamId']]);
									Header('Location: ./manageteams/t/' . $curTeam['teamId']);
									return;
								}
							}
						?>
							<div class="pmd-card pmd-z-depth">
								<div class="pmd-tabs pmd-tabs-bg">
									<div class="pmd-tab-active-bar"></div>

									<ul role="tablist" class="nav nav-tabs nav-justified">
										<li class="active" role="presentation"><a data-toggle="tab" role="tab" aria-controls="overview" href="#overview">Overview</a></li>
										<li role="presentation"><a data-toggle="tab" role="tab" aria-controls="users" href="#users">Users</a></li>
										<li role="presentation"><a data-toggle="tab" role="tab" aria-controls="recruiting" href="#recruiting">Recruiting</a></li>
										<li role="presentation"><a data-toggle="tab" role="tab" aria-controls="settings" href="#settings">Settings</a></li>
									</ul>
								</div>
								<div class="pmd-card-body">
									<div class="tab-content">
										<div role="tabpanel" class="tab-pane active" id="overview">
											<table class="table" style="border: 0;">
												<tr>
													<td width="300">
														<div class="userbubble">
															<div class="bubble">
																<?php
																	foreach(Config::get('config/picture_extension') as $ext) {
																		if(file_exists('./resources/avatars/teams/' . $curTeam['teamId'] . '.' . $ext)) {
																			echo '<img src="./resources/avatars/teams/' . $curTeam['teamId'] . '.' . $ext . '" class="imageSize" />';
																		}
																	}
																?>
															</div>

															<div class="spacing"></div>

															<div class="user">
																<center><?php echo $curTeam['teamName']; ?></center>
															</div>
														</div>
													</td>

													<td>
														<label for=""></label>
													</td>
												</tr>
											</table>

										</div>

										<div role="tabpanel" class="tab-pane" id="users">
											<table class="table">
												<thead>
													<th width="30"></th><th>Username</th>
												</thead>

												<tbody>
													<?php
														foreach($allMembers as $member) {
															echo '<tr><td><img src="./resources/flags/' . $member['flag'] . '.png" style="outline: 0.5px solid black;"/></td><td>' . $member['userName'] . '</td></tr>';
														}
													?>
												</tbody>
											</table>
										</div>

										<div role="tabpanel" class="tab-pane" id="recruiting">
											<div class="row">
												<?php
													$allApplications = $db -> fetch('SELECT teamsignup.userId, users.username, teamsignup.motivation FROM teamsignup, users WHERE teamsignup.userId = users.userId AND teamId = ?', [$curTeam['teamId']], true);

													foreach($allApplications as $application) {
														$img = '';
														foreach(Config::get('config/picture_extension') as $ext) {
															if(file_exists('./resources/avatars/users/' . $application['userId'] . '.' . $ext)) {
																$img = '<img src="./resources/avatars/users/' . $application['userId'] . '.' . $ext . '" width="152" height="152" />';
															}
														}

														echo '<div class="col-xs-6" id="userCard' . $application['userId'] . '">
															<div class="pmd-card pmd-card-media-inline pmd-z-depth-3">
															    <div class="pmd-card-media">
															        <div class="media-body">
															            <h2 class="pmd-card-title-text">' . $application['username'] . '</h2>
															            <span class="pmd-card-subtitle-text">' . $application['motivation'] . '</span>
															        </div>

															        <div class="media-right media-middle">
															            <a href="./profile/u/' . $application['userId'] . '">' . $img . '</a>
															        </div>
															    </div>

															    <div class="pmd-card-actions">
															        <button class="btn pmd-ripple-effect btn-success pmd-btn-outline recruitUserAccept pmd-alert-toggle" type="button" data-id="' . $application['userId'] . '" data-positionX="right" data-positionY="top" data-effect="fadeInUp" data-type="success" data-message="You have accepted ' . $application['username'] . ' to your team.">Accept</button>
															        <button class="btn pmd-ripple-effect btn-danger pmd-btn-outline recruitUserDecline pmd-alert-toggle" type="button" data-id="' . $application['userId'] . '" data-positionX="right" data-positionY="top" data-effect="fadeInUp" data-type="error" data-message="You have declined ' . $application['username'] . ' to your team.">Decline</button>
															    </div>
															</div>
														</div>';
													}
												?>
											</div>
										</div>

										<div role="tabpanel" class="tab-pane" id="settings">
											<?php
												$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

												echo $_GET['t'];
											?>
											<form action="./" method="post" enctype="multipart/form-data">
												<table class="table">
													<thead>
														<th>Setting</th><th>Value</th>
													</thead>

													<tbody>
														<tr>
															<td><label>Avatar</label></td>
															<td>
																<input id="avatarSelect" type="file" name="avatar" />
															</td>
														</tr>

														<tr>
															<td><label>Recruiting</label></td>
															<td>
																<div class="pmd-switch">
															        <label>
															            <input id="recruitingSwitch" type="checkbox" <?php echo ($curTeam['teamRecruiting']) ? 'checked' : ''; ?> />
															            <span class="pmd-switch-label"></span>
															        </label>
															    </div>
															</td>
														</tr>

														<tr>
															<td colspan="2">
																<div class="form-group pmd-textfield">
																   <label for="requirements" class="control-label">Requirements</label>
																   <textarea class="form-control" rows="6" name="requirements" style="height: 150px;"><?php echo isset($_POST['requirements']) ? $_POST['requirements'] : $curTeam['teamRequirements']; ?></textarea>
																</div>
															</td>
														</tr>
													</tbody>

													<tfooter>
														<tr>
															<td colspan="2"><button type="submit" class="btn btn-success pull-right">Save</button></td>
														</tr>
													</tfooter>
												</table>
											</form>
										</div>
									</div>
								</div>
							</div>
						<?php
						}
						else {
							echo '<ol class="breadcrumb"><li><a href="./">Home</a></li><li><a href="./manageteams">Manage your teams</a></li><li class="active">Invalid team</li></ol>';
							echo '<div class="alert alert-danger">You are not a part of this team and therefore you can not access this page. </div>';
						}
						// echo '<pre>',print_r($allMembers),'</pre>';
					}
					else {
						echo '<ol class="breadcrumb"><li><a href="./">Home</a></li><li><a href="./manageteams">Manage your teams</a></li><li class="active">Invalid team</li></ol>';
						echo '<div class="alert alert-danger">Invalid team. </div>';
					}
				}
				else {
				?>
				<div class="row">
					<div class="col-xs-12">
						<div class="pmd-z-depth inboxTile">
							<table class="table table-hover">
								<thead>
									<th>Teamname</th><th>Team owner</th><th>Main mode</th><th>Ranking points</th>
								</thead>

								<tbody>
									<?php
										$ownedTeams = $db -> fetch('SELECT teamId, teamName, teamMainMode, teamRankingScore, users.userName FROM teams, users WHERE teams.teamOwnerUserId = users.userId AND teamOwnerUserId = ?', [$userData['userId']], true);

										foreach($ownedTeams as $team) {
											echo '<tr class="clickableRow" data-href="./manageteams/' . $team["teamId"] . '"><td>' . $team['teamName'] .' (' . $team['teamId'] . ')</td><td><b>' . $team['userName'] . '</b></td><td>' . $team['teamMainMode'] . '</td><td>' . $team['teamRankingScore'] . '</td></tr>';
										}

										$partOfTeam = $db -> fetch('SELECT teammembers.teamId, teams.teamName, teams.teamMainMode, teams.teamRankingScore, users.userName FROM teammembers, teams, users WHERE teammembers.teamId = teams.teamId AND users.userId = teams.teamOwnerUserId AND teammembers.userId = ? AND teams.teamOwnerUserId <> ?', [$userData['userId'], $userData['userId']], true);

										foreach($partOfTeam as $team) {
											echo '<tr class="clickableRow" data-href="./manageteams/' . $team["teamId"] . '"><td>' . $team['teamName'] .' (' . $team['teamId'] . ')</td><td>' . $team['userName'] . '</td><td>' . $team['teamMainMode'] . '</td><td>' . $team['teamRankingScore'] . '</td></tr>';
										}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>

				<?php } ?>
		</div>

		<?php include_once('./resources/includes/loginpopup.php'); ?>
	</body>

	<script src="js/jquery-3.2.0.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/propeller.min.js"></script>
	<script>
		$(document).ready(function($) {
			$("#avatarSelect").on('change', function() {

			});

			$(".recruitUserAccept").on('click', function() {
				var acceptuser = $(this).data('id');

				$.ajax({
					url: './recruiting_ajax.php',
					data: {
						action: 'acceptRecruitment',
						userId: acceptuser,
						teamId: <?php echo isset($_GET['t']) ? $_GET['t'] : 'invalid'; ?>
					},
					type: 'post',
					success: function(evt) {
						$('#userCard' + acceptuser).toggle('scale');
					}
				});
			});

			$(".recruitUserDecline").on('click', function() {
				var acceptuser = $(this).data('id');

				$.ajax({
					url: './recruiting_ajax.php',
					data: {
						action: 'declineRecruitment',
						userId: acceptuser,
						teamId: <?php echo isset($_GET['t']) ? $_GET['t'] : 'invalid'; ?>
					},
					type: 'post',
					success: function(evt) {
						$('#userCard' + acceptuser).fadeOut();
					}
				});
			});

			$(".clickableRow").click(function() {
				window.location = $(this).data("href");
			});

			$('.pmd-tabs').pmdTab();

			$("#recruitingSwitch").on('click', function() {
				var checked = $("#recruitingSwitch").prop("checked") ? 1 : 0;
				$.ajax({
					url: './recruiting_ajax.php',
					data: {
						action: 'recruiting',
						recruiting: checked,
						userId: <?php echo $userData['userId']; ?>,
						teamId: <?php echo isset($_GET['t']) ? $_GET['t'] : 'invalid'; ?>
					},
					type: 'post'
				});
			});
		});
	</script>
</html>
