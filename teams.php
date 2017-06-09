<?php
	require_once 'core/init.php';
	require_once 'resources/includes/bbparser/stringparser_bbcode.class.php';
	require_once 'resources/includes/bbcode.php';

	$db = new Db(Config::get('mysql/host'), Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));
	$db -> setFetchMode(PDO::FETCH_ASSOC);
	$userData = User::authenticate(isset($_COOKIE[Config::get('cookie/cookie_name')]) ? $_COOKIE[Config::get('cookie/cookie_name')] : null);
	$serverSettings = $db -> fetch('SELECT * FROM serverSettings');

	$curPage = 'teams';
?>

<!DOCTYPE html>
<html>
	<head>
		<?php include_once("./resources/includes/meta.php"); ?>
		<?php include_once("./resources/includes/link.php"); ?>

		<title>Teams - CustomAllOsu</title>
	</head>

	<body>
		<?php include_once("./resources/includes/banner.php"); ?>
		<?php include_once("./resources/includes/navbar.php"); ?>

		<aside id="sidebar" class="sidebarshow">
			<nav class="list-group">
				<a href="./teams" class="list-group-item <?php echo !isset($_GET['t']) ? 'active' : '';?>">All teams</a>
				<a href="./manageteams" class="list-group-item">Manage your teams</a>
				<a href="./createteam" class="list-group-item">Create a team</a>
			</nav>
		</aside>

		<div class="content">
			<div class="row">
				<?php
					if(isset($_GET['t'])) {
						$curTeam = $db -> fetch('SELECT teams.teamId, teams.teamName, teams.teamOwnerUserId, users.username, teams.teamMainMode, teams.teamDateCreated, teams.teamRecruiting, teams.teamRequirements, teams.teamRankingScore FROM teams, users WHERE teams.teamOwnerUserId = users.userId AND teamId = ?', [$_GET['t']]);

						if($curTeam) {
							echo '<ol class="breadcrumb"><li><a href="./">Home</a></li><li><a href="./teams">All teams</a></li><li class="active">' . $curTeam['teamName'] . '</li></ol>';
							?>
							<div class="col-xs-12">
								<?php
									if(isset($_GET['signedup'])) {
										echo '<div class="alert alert-success"><b>You have send your application!</b></div>';
									}

									$isPartOfTeam = $db -> fetch('SELECT userId FROM teammembers WHERE teamId = ? AND userId = ?', [$curTeam['teamId'], $userData['userId']]);

									if(!$isPartOfTeam) {
										$isSignedUp = $db -> fetch('SELECT teamId FROM teamsignup WHERE teamId = ? AND userId = ?', [$curTeam['teamId'], $userData['userId']]);

										if($isSignedUp) {
											echo '<div class="alert alert-info">
												<center><b>You have signed up for this team. All you have to do now is wait!</b></center>
											</div>';
										}
										else {
											if($curTeam['teamRecruiting'] == 1) {
												echo '<div class="alert alert-info">
														<center><b>This team is recruiting members!</b></center> <br />
														<b>Requirements for joining this team:</b><br />' . $bbcode->parse($curTeam['teamRequirements']) . '
													</div>';
												echo '<div class="row">
													<div class="col-xs-12">
														<a href="./jointeam/' . $curTeam['teamId'] . '" class="btn pmd-btn-raised btn-success pull-right"><i class="fa fa-sign-in"></i> Sign up to this team</a>
													</div>
												</div>';
											}
										}
									}
								?>

								<div class="extraSpacing3"></div>

								<div class="row">
									<div class="col-lg-3">
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
									</div>

									<div class="col-lg-9">
										<table class="table table-hover">
											<thead>
												<th width="40"></th><th width="50">Username</th><th>Main mode</th><th>Profile</th>
											</thead>

											<tbody>
											<?php
												$teamMembers = $db -> fetch('SELECT teammembers.userId, teams.teamOwnerUserId, teammembers.teamId, users.userName, users.flag, users.userMainMode, users.osuProfile FROM teams, teammembers, users WHERE teammembers.userId = users.userId AND teammembers.teamId = teams.teamId AND teammembers.teamId = ? ORDER BY dateJoined ASC', [$curTeam['teamId']], true);

												foreach($teamMembers as $member) {
													if($member['teamOwnerUserId'] == $member['userId'])
														$teamOwner = '<b>' . $member['userName'] . '</b>';
													else
														$teamOwner = $member['userName'];

													echo '<tr class="clickableRow" data-href="./profile/p/' . $member['userId'] . '"><td><img src="./resources/flags/' . $member['flag'] . '.png" /></td><td>' . $teamOwner . '</td><td>' . $member['userMainMode'] . '</td><td><a href="' . $member['osuProfile'] . '">' . $member['osuProfile'] . '</a></td></tr>';
												}
											?>
											</tbody>
										</table>
									</div>
								</div>

								<div class="extraSpacing3"></div>

								<div class="row">
									<div class="col-xs-12">
										<div class="pmd-z-depth inboxTile">
											<ul>
												<li>Main mode</li>
												<li>Highest ranked place in any tournament</li>
												<li>Place on leaderboard</li>
												<li>Team activity</li>
											</ul>
										</div>
									</div>
								</div>
							</div>

							<?php
						}
						else {
							echo '<ol class="breadcrumb"><li><a href="./">Home</a></li><li><a href="./teams">All teams</a></li><li class="active">Invalid team</li></ol>';
							echo '<div class="alert alert-danger">Invalid team. </div>';
						}
					}
					else {
				?>

				<div class="col-xs-12">
					<div class="pmd-z-depth inboxTile">
						<table class="table table-hover" id="tableVertical">
							<thead>
								<th width="140">Avatar</th><th>Team name</th><th>Team owner</th><th>Team main mode</th><th>Recruiting</th><th>Team score</th>
							</thead>

							<tbody>
								<?php
									$allTeams = $db -> fetch('SELECT teams.teamId, teams.teamName, teams.teamOwnerUserId, users.username, teams.teamMainMode, teams.teamDateCreated, teams.teamRecruiting, teams.teamRankingScore FROM teams, users WHERE teams.teamOwnerUserId = users.userId', null, true);

									foreach($allTeams as $team) {
										$img = '';
										foreach(Config::get('config/picture_extension') as $ext) {
											if(file_exists('./resources/avatars/teams/' . $team['teamId'] . '.' . $ext)) {
												$img = '<img src="./resources/avatars/teams/' . $team['teamId'] . '.' . $ext . '" width="128" height="128" />';
											}
										}

										echo '<tr class="clickableRow" data-href="./teams/' . $team["teamId"] . '"><td>' . $img . '</td><td>' . $team['teamName'] . '</td><td>' . $team['username'] . '</td><td>' . $team['teamMainMode'] . '</td><td>' . (($team['teamRecruiting'] == 1) ? '<i class="fa fa-check-circle fa-3x"><i>' : '<i class="fa fa-times-circle fa-3x"></i>') . '</td><td>' . $team['teamRankingScore'] . '</td></tr>';
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
				<?php }	?>
			</div>
		</div>

		<?php include_once('./resources/includes/loginpopup.php'); ?>
	</body>

	<script src="js/jquery-3.2.0.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/propeller.min.js"></script>
	<script>
		$(document).ready(function($) {
		    $(".clickableRow").click(function() {
		        window.location = $(this).data("href");
		    });
		});
	</script>
</html>
