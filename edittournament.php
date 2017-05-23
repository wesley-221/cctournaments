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
		<?php include_once("./resources/includes/meta.php"); ?>
		<?php include_once("./resources/includes/link.php"); ?>

		<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
		<link href="http://propeller.in/components/select2/css/select2-bootstrap.css" rel="stylesheet" />
		<link href="http://propeller.in/components/select2/css/pmd-select2.css" rel="stylesheet" />

		<link href="http://propeller.in/components/datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet" />
		<link href="http://propeller.in/components/datetimepicker/css/pmd-datetimepicker.css" rel="stylesheet" />

		<title>Edit tournament - CustomAllOsu</title>
	</head>

	<body>
		<?php include_once("./resources/includes/banner.php"); ?>
		<?php include_once("./resources/includes/navbar.php"); ?>

		<?php
			if(isset($_GET['t'])) {
				$curTournament = $db -> fetch('SELECT tournamentId, tournamentName, tournaments.tournamentOwnerUserId, username AS tournamentOwner, tournamentVerified, tournamentSummary, tournamentContent, tournamentMode, tournamentSlots, tournamentTeamFormat, tournamentRegistrationEndDate, tournamentStartDate, tournamentRankingPoints FROM tournaments, users WHERE tournaments.tournamentOwnerUserId = users.userId AND tournamentId = ?', [$_GET['t']]);

				if($curTournament) {
				?>
					<aside id="sidebar" class="sidebarshow">
						<nav class="list-group">
							<a href="./admin/tournaments/edit/<?php echo $_GET['t']; ?>/general" class="list-group-item <?php echo (!isset($_GET['o']) || $_GET['o'] == "general") ? "active" : ""; ?>">General</a>
							<a href="./admin/tournaments/edit/<?php echo $_GET['t']; ?>/participants" class="list-group-item <?php echo ($_GET['o'] == "participants") ? "active" : ""; ?>">Participants</a>
							<a href="./admin/tournaments/edit/<?php echo $_GET['t']; ?>/settings" class="list-group-item <?php echo ($_GET['o'] == "settings") ? "active" : ""; ?>">Settings</a>
							<!-- <a href="./admin/tournaments/edit/<?php echo $_GET['t']; ?>/" class="list-group-item"></a> -->
						</nav>
					</aside>

					<div class="content">
						<ol class="breadcrumb">
							<li><a href="./">Home</a></li>
							<li><a href="./admin">Admin panel</a></li>
							<li><a href="./admin/tournaments">Manage tournaments</a></li>
							<li><a href="./admin/tournaments/edit">Edit a tournament</a></li>
							<li class="active"><?php echo $curTournament['tournamentName']?></li>
						</ol>

						<?php
							if(!isset($_GET['o']) || !strcmp($_GET['o'], "general")) {
								if($_SERVER['REQUEST_METHOD'] == 'POST') {

									echo '<pre>',print_r($_POST),'</pre>';
								}
								?>
									<form action="./admin/tournaments/edit/<?php echo $_GET['t']; ?>/general" method="post">
										<div class="pmd-card pmd-card-default pmd-z-depth pmd-card-custom-form" style="padding: 8px;">
											<div class="row">
												<div class="col-xs-6">
													<div class="form-group pmd-textfield">
														<label for="tournamentName" class="control-label">Tournament name</label>
														<input type="text" id="tournamentName" name="tournamentName" class="form-control" value="<?php echo $curTournament['tournamentName']; ?>" />
													</div>
												</div>

												<div class="col-xs-6">
													<div class="form-group pmd-textfield">
														<label for="tournamentOwner" class="control-label">Tournament owner</label>
														<input type="text" id="tournamentOwner" class="form-control" value="<?php echo $curTournament['tournamentOwner']; ?>" disabled />
													</div>
												</div>
											</div>

											<div class="row">
												<div class="col-xs-6">
													<div class="form-group pmd-textfield">
														<label for="tournamentMode" class="control-label">Tournament mode</label>
														<select id="tournamentMode" name="tournamentMode" class="select-simple form-control pmd-select2">
															<option value="osu!Standard" <?php echo ($curTournament['tournamentMode'] == "osu!Standard") ? "selected" : ""; ?>>osu!Standard</option>
															<option value="osu!Taiko" <?php echo ($curTournament['tournamentMode'] == "osu!Taiko") ? "selected" : ""; ?>>osu!Taiko</option>
															<option value="osu!Catch" <?php echo ($curTournament['tournamentMode'] == "osu!Catch") ? "selected" : ""; ?>>osu!Catch</option>
															<option value="osu!Mania" <?php echo ($curTournament['tournamentMode'] == "osu!Mania") ? "selected" : ""; ?>>osu!Mania</option>
														</select>
													</div>
												</div>

												<div class="col-xs-6">
													<div class="form-group pmd-textfield">
														<label for="tournamentFormat" class="control-label">Tournament format</label>
														<select id="tournamentFormat" name="tournamentFormat" class="select-simple form-control pmd-select2">
															<option value="1v1" <?php echo ($curTournament['tournamentMode'] == "1v1") ? "selected" : ""; ?>>1v1</option>
															<option value="2v2" <?php echo ($curTournament['tournamentMode'] == "2v2") ? "selected" : ""; ?>>2v2</option>
															<option value="3v3" <?php echo ($curTournament['tournamentMode'] == "3v3") ? "selected" : ""; ?>>3v3</option>
															<option value="4v4" <?php echo ($curTournament['tournamentMode'] == "4v4") ? "selected" : ""; ?>>4v4</option>
															<option value="5v5" <?php echo ($curTournament['tournamentMode'] == "5v5") ? "selected" : ""; ?>>5v5</option>
														</select>
													</div>
												</div>
											</div>

											<div class="row">
												<div class="col-xs-6">
													<div class="form-group pmd-textfield">
														<label for="tournamentRegistrationEndDate" class="control-label">Tournament registration end date</label>
														<input id="tournamentRegistrationEndDate" name="tournamentRegistrationEndDate" type="text" class="form-control" value="<?php echo date('d/m/Y', strtotime($curTournament['tournamentRegistrationEndDate'])); ?>" />
													</div>
												</div>

												<div class="col-xs-6">
													<div class="form-group pmd-textfield">
														<label for="tournamentStartDate" class="control-label">Tournament start date</label>
														<input id="tournamentStartDate" name="tournamentStartDate" type="text" class="form-control" value="<?php echo date('d/m/Y', strtotime($curTournament['tournamentStartDate'])); ?>" />
													</div>
												</div>
											</div>

											<div class="row">
												<div class="col-xs-12">
													<div class="form-group pmd-textfield">
														<label for="tournamentSlots" class="control-label">Tournament slots</label>
														<input type="number" id="tournamentSlots" class="form-control" value="<?php echo $curTournament['tournamentSlots']; ?>" />
													</div>
												</div>
											</div>

											<div class="row">
												<div class="col-xs-12">
													<button type="submit" class="btn btn-success pull-right"><i class="fa fa-floppy-o"></i> Save</button>
												</div>
											</div>
										</div>
									</form>
								<?php
							}
							else if(!strcmp($_GET['o'], "participants")) {
								if($curTournament['tournamentTeamFormat'] === "1v1") {
									?>
										<div class="pmd-card pmd-card-default pmd-z-depth pmd-card-custom-form" style="padding: 8px;">
											1v1
										</div>
									<?php
								}
								else {
									$allTeams = $db -> fetch('SELECT distinct(teamId) FROM tournamentsignups WHERE tournamentId = ?', [$_GET['t']], true);

									foreach($allTeams as $team) {
										$teamParticipants = $db -> fetch('SELECT * FROM tournamentsignups WHERE tournamentId = ? AND teamId = ?', [$_GET['t'], $team['teamId']], true);
										$curTeam = $db -> fetch('SELECT * FROM teams WHERE teamId = ?', [$team['teamId']]);
										?>
											<div class="pmd-card pmd-card-default pmd-z-depth pmd-card-custom-form" style="padding: 8px;">
												<div class="row">
													<div class="col-xs-2">
														<?php
															foreach(Config::get('config/picture_extension') as $ext) {
																if(file_exists('./resources/avatars/teams/' . $curTeam['teamId'] . '.' . $ext)) {
																	echo '<a href="./teams/' . $curTeam['teamId'] . '"><img src="./resources/avatars/teams/' . $curTeam['teamId'] . '.' . $ext . '" class="imageSize" /></a>';
																}
															}
														?>
													</div>

													<div class="col-xs-8">
														<?php
															foreach($teamParticipants as $participant) {
																foreach(Config::get('config/picture_extension') as $ext) {
																	if(file_exists('./resources/avatars/users/' . $participant['userId'] . '.' . $ext)) {
																		echo '<a href="./profile/' . $participant['userId'] . '"><img src="./resources/avatars/users/' . $participant['userId'] . '.' . $ext . '" width="100" height="100" style="margin-right: 3px;" /></a>';
																	}
																}
															}
														?>
													</div>

													<div class="col-xs-2">
														<button class="btn btn-success pull-right">Accept</button> <br><br>
														<button class="btn btn-danger pull-right">Decline</button>
													</div>
												</div>

											</div>
										<?php
									}
								}
							}
							else if(!strcmp($_GET['o'], "settings")) {

							}
						?>
					</div>
				<?php
				}
				else {
					echo '<div class="container">
						<ol class="breadcrumb">
							<li><a href="./">Home</a></li>
							<li><a href="./admin">Admin panel</a></li>
							<li><a href="./admin/tournaments">Manage tournaments</a></li>
							<li><a href="./admin/tournaments/edit">Edit a tournament</a></li>
							<li class="active">Invalid tournament</li>
						</ol>
						<div class="alert alert-danger">Invalid tournament</div>
					</div>';
				}
			}
		?>

		<?php include_once('./resources/includes/loginpopup.php'); ?>
	</body>

	<script src="js/jquery-3.2.0.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/propeller.min.js"></script>
	<script type="text/javascript" src="http://propeller.in/components/select2/js/select2.full.js"></script>
	<script type="text/javascript" src="http://propeller.in/components/select2/js/pmd-select2.js"></script>
	<script type="text/javascript" language="javascript" src="http://propeller.in/components/datetimepicker/js/moment-with-locales.js"></script>
	<script type="text/javascript" language="javascript" src="http://propeller.in/components/datetimepicker/js/bootstrap-datetimepicker.js"></script>
	<script>
		$('#tournamentRegistrationEndDate, #tournamentStartDate').datetimepicker({'format': "MM/DD/YYYY", 'keepOpen': true});

		$('.pmd-tabs').pmdTab();

		$(".select-simple").select2({
			theme: "bootstrap",
			minimumResultsForSearch: Infinity,
		});
	</script>
</html>
