<?php
	require_once 'core/init.php';
	require_once 'resources/includes/bbparser/stringparser_bbcode.class.php';
	require_once 'resources/includes/bbcode.php';

	$db = new Db(Config::get('mysql/host'), Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));
	$db -> setFetchMode(PDO::FETCH_ASSOC);
	$userData = User::authenticate(isset($_COOKIE[Config::get('cookie/cookie_name')]) ? $_COOKIE[Config::get('cookie/cookie_name')] : null);
	$serverSettings = $db -> fetch('SELECT * FROM serverSettings');

	$curPage = 'tournaments';

	// if($_SERVER['REQUEST_METHOD'] == 'POST') {
	// 	if(isset($_GET['t'])) {
	// 		$qTournament = $db -> fetch('SELECT tournamentId FROM tournaments WHERE tournamentId = ? LIMIT 1', [$_GET['t']]);
	//
	// 		if($qTournament) {
	// 			$qSignup = $db ->fetch('SELECT * FROM tournamentSignups WHERE tournamentId = ? AND userId = ? LIMIT 1', [$_GET['t'], $userData['userId']]);
	//
	// 			if($qSignup) {
	// 				echo $_GET['t'];
	// 				echo $userData['userId'];
	// 				$db -> execute('DELETE FROM tournamentSignups WHERE tournamentId = ? AND userId = ?', [$_GET['t'], $userData['userId']]);
	// 				Header('Location: ./tournaments/' . $_GET['t']);
	// 				return;
	// 			}
	// 			else {
	// 				$db -> execute('INSERT INTO tournamentSignups VALUES(?, ?)', [$_GET['t'], $userData['userId']]);
	// 				Header('Location: ./tournaments/' . $_GET['t']);
	// 				return;
	// 			}
	// 		}
	// 	}
	// }

	if(isset($_GET['mode'])) {
		$stdActive = ($_GET['mode'] == 'std') ? 'active' : '';
		$taikoActive = ($_GET['mode'] == 'taiko') ? 'active' : '';
		$catchActive = ($_GET['mode'] == 'catch') ? 'active' : '';
		$maniaActive = ($_GET['mode'] == 'mania') ? 'active' : '';
	}
	else {
		if(isset($_GET['t'])) {

		}
		else {
			$stdActive = 'active';
		}
	}

	$stdTourneyCount = $db -> fetch('SELECT count(tournamentId) AS count FROM tournaments WHERE tournamentMode = "osu!Standard"');
	$taikoTourneyCount = $db -> fetch('SELECT count(tournamentId) AS count FROM tournaments WHERE tournamentMode = "osu!Taiko"');
	$catchTourneyCount = $db -> fetch('SELECT count(tournamentId) AS count FROM tournaments WHERE tournamentMode = "osu!Catch"');
	$maniaTourneyCount = $db -> fetch('SELECT count(tournamentId) AS count FROM tournaments WHERE tournamentMode = "osu!Mania"');
?>

<!DOCTYPE html>
<html>
	<head>
		<?php include_once("./resources/includes/meta.php"); ?>
		<?php include_once("./resources/includes/link.php"); ?>

		<title>Tournaments - CustomAllOsu</title>
	</head>

	<body>
		<?php include_once("./resources/includes/banner.php"); ?>
		<?php include_once("./resources/includes/navbar.php"); ?>

		<aside id="sidebar" class="sidebarshow">
			<nav class="list-group">
				<a href="./tournaments/mode/std" class="list-group-item <?php echo $stdActive; ?>">osu!Standard <span class="badge"><?php echo $stdTourneyCount['count']; ?></span></a>
				<a href="./tournaments/mode/taiko" class="list-group-item <?php echo $taikoActive; ?>">osu!Taiko <span class="badge"><?php echo $taikoTourneyCount['count']; ?></span></a>
				<a href="./tournaments/mode/catch" class="list-group-item <?php echo $catchActive; ?>">osu!Catch <span class="badge"><?php echo $catchTourneyCount['count']; ?></span></a>
				<a href="./tournaments/mode/mania" class="list-group-item <?php echo $maniaActive; ?>">osu!Mania <span class="badge"><?php echo $maniaTourneyCount['count']; ?></span></a>
			</nav>
		</aside>

		<div class="content">
			<?php
				if(isset($_GET['mode'])) {
			?>

			<div class="pmd-z-depth inboxTile">
				<table class="table table-hover">
					<thead>
						<th>Tournament name</th><th>Player/team slots</th><th>Format</th><th>Registration end date</th><th>Start date</th><th>Ranking points</th>
					</thead>

					<tbody>
						<?php
							$allTournaments = $db -> fetch('SELECT * FROM tournaments', null, true);

							foreach($allTournaments as $tournament) {
								if(!strcmp($tournament['tournamentTeamFormat'], '1v1')) {
									$curSignups = $db -> fetch('SELECT count(tournamentId) AS signups FROM tournamentsignups WHERE tournamentId = ?', [$tournament["tournamentId"]]);
								}
								else {
									$curSignups = $db -> fetch('SELECT count(DISTINCT(teamID)) AS signups FROM tournamentsignups WHERE tournamentId = ?', [$tournament['tournamentId']]);
								}

								if(isset($_GET['mode'])) {
									if($_GET['mode'] == "std" && $tournament["tournamentMode"] == "osu!Standard") {
										echo '<tr class="clickableRow" data-href="./tournaments/t/' . $tournament["tournamentId"] . '"><td>' . $tournament["tournamentName"] . '</td><td>' . $curSignups['signups'] . '/' . $tournament["tournamentSlots"] . '</td><td>' . $tournament["tournamentTeamFormat"] . '</td><td>' . date('d/m/Y', strtotime($tournament["tournamentRegistrationEndDate"])) . '</td><td>' . date('d/m/Y', strtotime($tournament["tournamentStartDate"])) . '</td><td>' . $tournament["tournamentRankingPoints"] . '</td></tr>';
									}
									else if($_GET['mode'] == "taiko" && $tournament["tournamentMode"] == "osu!Taiko") {
										echo '<tr class="clickableRow" data-href="./tournaments/t/' . $tournament["tournamentId"] . '"><td>' . $tournament["tournamentName"] . '</td><td>' . $curSignups['signups'] . '/' . $tournament["tournamentSlots"] . '</td><td>' . $tournament["tournamentTeamFormat"] . '</td><td>' . date('d/m/Y', strtotime($tournament["tournamentRegistrationEndDate"])) . '</td><td>' . date('d/m/Y', strtotime($tournament["tournamentStartDate"])) . '</td><td>' . $tournament["tournamentRankingPoints"] . '</td></tr>';
									}
									else if($_GET['mode'] == "catch" && $tournament["tournamentMode"] == "osu!Catch") {
										echo '<tr class="clickableRow" data-href="./tournaments/t/' . $tournament["tournamentId"] . '"><td>' . $tournament["tournamentName"] . '</td><td>' . $curSignups['signups'] . '/' . $tournament["tournamentSlots"] . '</td><td>' . $tournament["tournamentTeamFormat"] . '</td><td>' . date('d/m/Y', strtotime($tournament["tournamentRegistrationEndDate"])) . '</td><td>' . date('d/m/Y', strtotime($tournament["tournamentStartDate"])) . '</td><td>' . $tournament["tournamentRankingPoints"] . '</td></tr>';
									}
									else if($_GET['mode'] == "mania" && $tournament["tournamentMode"] == "osu!Mania") {
										echo '<tr class="clickableRow" data-href="./tournaments/t/' . $tournament["tournamentId"] . '"><td>' . $tournament["tournamentName"] . '</td><td>' . $curSignups['signups'] . '/' . $tournament["tournamentSlots"] . '</td><td>' . $tournament["tournamentTeamFormat"] . '</td><td>' . date('d/m/Y', strtotime($tournament["tournamentRegistrationEndDate"])) . '</td><td>' . date('d/m/Y', strtotime($tournament["tournamentStartDate"])) . '</td><td>' . $tournament["tournamentRankingPoints"] . '</td></tr>';
									}
								}
							}
						?>
					</tbody>
				</table>
			</div>

			<?php }
			else {
				if(isset($_GET['t'])) {
					$curTournament = $db -> fetch('SELECT * FROM tournaments WHERE tournamentId = ?', [$_GET['t']]);

					if($curTournament) {
						echo '<ol class="breadcrumb"><li><a href="./">Home</a></li><li><a href="./tournaments">Tournaments</a></li><li class="active">' . $curTournament["tournamentName"] . '</li></ol>';

						if($userData['loggedin']) {
							$signedUp = $db -> fetch('SELECT * FROM tournamentSignups WHERE tournamentId = ? AND userId = ?', [$_GET['t'], $userData['userId']]);

							if($signedUp) {
								echo '<div class="alert alert-success"><center><b>You are signed up for this tournament.</b></center></div>';
							}
						}

						if($_SERVER['REQUEST_METHOD'] == 'POST') {
							$sFinalString = '';

							if(!strcmp($curTournament['tournamentTeamFormat'], '1v1')) {
								$isAlreadyParticipating = $db -> fetch('SELECT tournamentsignups.userId, username FROM tournamentsignups, users WHERE tournamentsignups.userId = users.userId AND tournamentId = ? AND tournamentsignups.userId = ?', [$_GET['t'], $userData['userId']]);

								if(!$isAlreadyParticipating) {
									$db -> execute('INSERT INTO tournamentsignups(tournamentId, userId) VALUES(?, ?)', [$_GET['t'], $userData['userId']]);
									Header('Location: ../../tournaments/t/' . $_GET['t']);
									return;
								}
								else {
									$db -> execute('DELETE FROM tournamentsignups WHERE tournamentId = ? AND userId = ?', [$_GET['t'], $userData['userId']]);
									Header('Location: ../../tournaments/t/' . $_GET['t']);
									return;
								}
							}
							else {
								if(isset($_POST['signInTeam'])) {
									if(isset($_POST['teams'])) {
										if(count($_POST['teams']) === 1) {
											// check if a person is already participating in this tournament
											foreach($_POST['teams'] as $team) {
												foreach($team as $userid => $value) {
													$isAlreadyParticipating = $db -> fetch('SELECT tournamentsignups.userId, username FROM tournamentsignups, users WHERE tournamentsignups.userId = users.userId AND tournamentId = ? AND tournamentsignups.userId = ?', [$_GET['t'], $userid]);

													if($isAlreadyParticipating) {
														$sFinalString .= '"' . $isAlreadyParticipating['username'] . '" is already participating in this tournament. <br />';
													}
												}

												// check if there are at least 2 people for a 2v2, 3 people for a 3v3 etc
												if(!strcmp($curTournament['tournamentTeamFormat'], '2v2')) {
													if(count($team) < 2) {
														$sFinalString .= 'This tournament is a 2v2. You need to at least select 2 people to join to participate as a team. <br />';
													}
												}
												else if(!strcmp($curTournament['tournamentTeamFormat'], '3v3')) {
													if(count($team) < 3) {
														$sFinalString .= 'This tournament is a 3v3. You need to at least select 3 people to join to participate as a team. <br />';
													}
												}
												else if(!strcmp($curTournament['tournamentTeamFormat'], '4v4')) {
													if(count($team) < 4) {
														$sFinalString .= 'This tournament is a 4v4. You need to at least select 4 people to join to participate as a team. <br />';
													}
												}
												else if(!strcmp($curTournament['tournamentTeamFormat'], '5v5')) {
													if(count($team) < 5) {
														$sFinalString .= 'This tournament is a 5v5. You need to at least select 5 people to join to participate as a team. <br />';
													}
												}
											}

											if(strlen($sFinalString) > 0) {
												echo '<div class="alert alert-danger">' . $sFinalString . '</div>';
											}
											else {
												foreach($_POST['teams'] as $teamId => $members) {
													foreach($members as $member => $value) {
														$db -> execute('INSERT INTO tournamentsignups(tournamentId, userId, teamId) VALUES(?, ?, ?)', [$_GET['t'], $member, $teamId]);
													}
												}

												Header('Location: ../../tournaments/t/' . $_GET['t']);
											}
										}
										else {
											echo '<div class="alert alert-danger">You can only select players from <b>one team</b>.</div>';
										}
									}
									else {
										echo '<div class="alert alert-danger">You haven\'t selected any players to join this tournament with, please try again!</div>';
									}
								}
								else if(isset($_POST['signOutTeam'])) {
									$participatingTeam = $db -> fetch('SELECT teamId FROM tournamentsignups WHERE tournamentId = ? AND userId = ?', [$_GET['t'], $userData['userId']]);
									$participatingMembers = $db -> fetch('SELECT userId FROM tournamentsignups WHERE tournamentId = ? AND teamId = ?', [$_GET['t'], $participatingTeam['teamId']], true);

									foreach($participatingMembers as $member) {
										$db -> execute('DELETE FROM tournamentsignups WHERE userId = ? AND teamId = ?', [$member['userId'], $participatingTeam['teamId']]);
									}

									Header('Location: ../../tournaments/t/' . $_GET['t']);
									return;
								}
							}
						}
			?>

			<div class="row">
				<div class="col-xs-12">
					<?php echo $curTournament['tournamentSummary']; ?>
					<div class="extraSpacing3"></div>
					<?php
						if($userData['loggedin']) {
							if($signedUp) {
								echo '<form action="./tournaments/t/' . $_GET['t'] . '" method="post">
									<button type="submit" name="signOutTeam" class="btn btn-danger pull-right" style="font-size: 25px;"><i class="fa fa-sign-out"></i> Sign out!</button>
								</form>';
							}
							else {
								if(!strcmp($curTournament['tournamentTeamFormat'], '1v1')) {
									echo '<form action="tournaments/t/' . $_GET['t'] . '" method="post"><button type="submit" class="btn btn-success pull-right" style="font-size: 25px;"><i class="fa fa-sign-in"></i> Register!</button></form>';
								}
								else {
									echo '<button type="button" data-target="#signup" data-toggle="modal" class="btn btn-success pull-right" style="font-size: 25px;"><i class="fa fa-sign-in"></i> Register as team!</button>';
								}
							}
						}
					?>
				</div>
			</div>

			<div class="row" style="border: 1px solid black; margin-top: 27px; padding: 5px;">
				<div class="col-xs-12">
					<div class="row" style="overflow-x: auto;">
						<div class="col-xs-12">
							<table class="table">
								<thead>
									<th>Mode</th><th>Format</th><th>Registration end date</th><th>Start date</th><th>Ranking points</th>
								</thead>

								<tbody>
									<tr>
										<td><?php echo $curTournament['tournamentMode']; ?></td><td><?php echo $curTournament['tournamentTeamFormat']; ?></td><td><?php echo date('d/m/Y', strtotime($curTournament['tournamentRegistrationEndDate'])); ?></td>
										<td><?php echo date('d/m/Y', strtotime($curTournament['tournamentStartDate'])); ?></td><td><?php echo $curTournament['tournamentRankingPoints']; ?></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>

					<div class="extraSpacing3"></div>

					<div class="row">
						<div class="col-xs-12">
							<b>Registered <?php echo (!strcmp($curTournament['tournamentTeamFormat'], '1v1')) ? 'players' : 'teams'; ?>:</b>
							<div class="progress">
								<?php
									if(!strcmp($curTournament['tournamentTeamFormat'], '1v1')) {
										$signups = $db -> fetch('SELECT count(tournamentId) AS signups FROM tournamentsignups WHERE tournamentId = ?', [$curTournament['tournamentId']]);
										$percent = (100 / $curTournament['tournamentSlots']) * $signups['signups'];
									}
									else {
										$signups = $db -> fetch('SELECT tournamentId, count(DISTINCT(teamID)) as signups FROM tournamentsignups WHERE tournamentId = ?', [$curTournament['tournamentId']]);
										$percent = (100 / $curTournament['tournamentSlots']) * $signups['signups'];
									}
								?>

								<div class="progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="min-width: 3em; width: <?php echo $percent; ?>%;">
									<?php echo $signups['signups'] . '/' . $curTournament['tournamentSlots']; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<?php echo $bbcode->parse($curTournament['tournamentContent']); ?>
			</div>

			<?php
					}
					else {
						echo '<ol class="breadcrumb"><li><a href="./">Home</a></li><li><a href="./tournaments">Tournaments</a></li><li class="active">Invalid tournament</li></ol>';
						echo '<div class="alert alert-danger">Invalid tournament.</div>';
					}
				}
				else {
					echo '<div class="pmd-z-depth inboxTile">
					<table class="table table-hover">
						<thead>
							<th>Tournament name</th><th>Player/team slots</th><th>Format</th><th>Registration end date</th><th>Start date</th><th>Ranking points</th>
						</thead>

						<tbody>';
					$allTournaments = $db -> fetch('SELECT * FROM tournaments WHERE tournamentMode = "osu!Standard"', null, true);

					foreach($allTournaments as $tournament) {
						if(!strcmp($tournament['tournamentTeamFormat'], '1v1')) {
							$curSignups = $db -> fetch('SELECT count(tournamentId) AS signups FROM tournamentsignups WHERE tournamentId = ?', [$tournament["tournamentId"]]);
						}
						else {
							$curSignups = $db -> fetch('SELECT count(DISTINCT(teamID)) AS signups FROM tournamentsignups WHERE tournamentId = ?', [$tournament['tournamentId']]);
						}

						echo '<tr class="clickableRow" data-href="./tournaments/t/' . $tournament["tournamentId"] . '"><td>' . $tournament["tournamentName"] . '</td><td>' . $curSignups['signups'] . '/' . $tournament["tournamentSlots"] . '</td><td>' . $tournament["tournamentTeamFormat"] . '</td><td>' . date('d/m/Y', strtotime($tournament["tournamentRegistrationEndDate"])) . '</td><td>' . date('d/m/Y', strtotime($tournament["tournamentStartDate"])) . '</td><td>' . $tournament["tournamentRankingPoints"] . '</td></tr>';
					}

					echo '</tbody></table></div>';
				}
			} ?>
		</div>

		<div tabindex="-1" class="modal fade" id="signup" style="display: none;" aria-hidden="true">
			<form action="tournaments/t/<?php echo isset($_GET['t']) ? $_GET['t'] : null; ?>" method="post">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header pmd-modal-bordered">
							<h2 class="pmd-card-title-text">Select a team you want to participate with</h2>
						</div>

						<div class="modal-body">
							<div class="panel-group pmd-accordion pmd-accordion-nospace" id="allTeams" role="tablist" aria-multiselectable="false">
								<?php
									$allTeams = $db -> fetch('SELECT * FROM teams WHERE teamOwnerUserId = ?', [$userData['userId']], true);

									if($allTeams) {
										foreach($allTeams as $team) {
											echo'<div class="panel panel-info">
												<div class="panel-heading" role="tab" id="heading' . $team['teamId'] . '">
													<h4 class="panel-title"><a data-toggle="collapse" data-parent="#allTeams" href="#collapse' . $team['teamId'] . '" aria-expanded="true" data-expandable="true">' . $team['teamName'] . ' <i class="material-icons md-dark pmd-sm pmd-accordion-arrow">keyboard_arrow_up</i></a> </h4>
												</div>

												<div id="collapse' . $team['teamId'] . '" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
													<div class="panel-body">';

													$allMembers = $db -> fetch('SELECT teammembers.userId, users.userName FROM teammembers, users WHERE teammembers.userId = users.userId AND teammembers.teamId = ?', [$team['teamId']], true);

													foreach($allMembers as $member) {
														echo '<div class="checkbox pmd-default-theme">
														    <label class="pmd-checkbox pmd-checkbox-ripple-effect">
														        <input type="checkbox" name="teams[' . $team['teamId'] . '][' . $member['userId'] . ']" />
														        <span>' . $member['userName'] . '</span>
														    </label>
														</div>';
													}

													echo '</div>
												</div>
											</div>';
										}
									}
									else {
										ECHO 'FUCK YOU ARE NO TEAM OWNER GOODBYE';
									}
								?>
							</div>
						</div>

						<div class="pmd-modal-action pmd-modal-bordered text-right">
							<button type="submit" id="signupTeam" name="signInTeam" class="btn pmd-btn-flat pmd-ripple-effect btn-success" type="button">Sign up</button>
							<button data-dismiss="modal" class="btn pmd-btn-flat pmd-ripple-effect btn-danger" type="button">Cancel</button>
						</div>
					</div>
				</div>
			</form>
		</div>

		<?php include_once('./resources/includes/loginpopup.php'); ?>
	</body>

	<script src="js/jquery-3.2.0.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/propeller.min.js"></script>

	<script>
		$(document).ready(function() {
		    $(".clickableRow").click(function() {
		        window.location = $(this).data("href");
		    });
		});
	</script>
</html>
