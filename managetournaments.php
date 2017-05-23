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

	$sTournamentName 				= isset($_POST['tournamentName']) ? $_POST['tournamentName'] : '';
	$sTournamentMode 				= isset($_POST['tournamentMode']) ? $_POST['tournamentMode'] : '';
	$sTournamentSlots 				= isset($_POST['tournamentSlots']) ? $_POST['tournamentSlots'] : '';
	$sTournamentTeamFormat 			= isset($_POST['tournamentTeamFormat']) ? $_POST['tournamentTeamFormat'] : '';
	$sTournamentRegistrationEndDate = isset($_POST['tournamentRegistrationEndDate']) ? $_POST['tournamentRegistrationEndDate'] : '';
	$sTournamentStartDate 			= isset($_POST['tournamentStartDate']) ? $_POST['tournamentStartDate'] : '';
	$sTournamentRankingPoints 		= isset($_POST['tournamentRankingPoints']) ? $_POST['tournamentRankingPoints'] : '';
	$sTournamentSummary				= isset($_POST['tournamentSummary']) ? $_POST['tournamentSummary'] : '';
	$sTournamentDescription 		= isset($_POST['tournamentDescription']) ? $_POST['tournamentDescription'] : '';
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
		<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.13/css/jquery.dataTables.css">

		<title>Manage tournaments - CustomAllOsu</title>
	</head>

	<body>
		<?php include_once("./resources/includes/banner.php"); ?>
		<?php include_once("./resources/includes/navbar.php"); ?>

		<div class="container">
			<?php
				if(isset($_GET['p']) && $_GET['p'] == "create") {
			?>
			<ol class="breadcrumb">
				<li><a href="./">Home</a></li>
				<li><a href="./admin">Adminpanel</a></li>
				<li><a href="./admin/tournaments">Manage tournaments</a></li>
				<li class="active">Create a tournament</li>
			</ol>

			<?php
				if($_SERVER['REQUEST_METHOD'] == 'POST') {
					$sFinalString = '';

					if(strlen($sTournamentName) < 3 || strlen($sTournamentName) >= 50) {
						$sFinalString .= 'The name has to contain at least 3 characters and a maximum of 50. <br>';
					}

					if($sTournamentMode == "osu!Standard" || $sTournamentMode == "osu!Taiko" || $sTournamentMode == "osu!Catch" || $sTournamentMode == "osu!Mania") {} else{
						$sFinalString .= 'Invalid mode, please select a valid one. <br>';
					}

					if($sTournamentSlots < 16) {
						$sFinalString .= 'The minimum amount of slots is 16. <br>';
					}

					if($sTournamentTeamFormat == "1v1" || $sTournamentTeamFormat == "2v2" || $sTournamentTeamFormat == "3v3" || $sTournamentTeamFormat == "4v4" || $sTournamentTeamFormat == "5v5") {} else {
						$sFinalString .= 'Invalid team format, please select a valid one. <br>';
					}

					if(!Functions::checkIsAValidDate($sTournamentRegistrationEndDate)) {
						$sFinalString .= 'The registration end date is invalid. Please select a valid date. <br>';
					}

					if(!Functions::checkIsAValidDate($sTournamentStartDate)) {
						$sFinalString .= 'The start date is invalid. Please select a valid date. <br>';
					}

					if($sTournamentRankingPoints <= 0) {
						$sFinalString .= 'The ranking points is invalid. You need to award at least 0 points to a tournament. <br>';
					}

					if(strlen($sTournamentSummary) < 3 || strlen($sTournamentSummary) >= 1000) {
						$sFinalString .= 'The summary has to contain at least 3 characters and a maximum of 1000. <br>';
					}

					if(strlen($sTournamentDescription) < 3) {
						$sFinalString .= 'The description has to contain at least 3 characters. <br>';
					}

					if(strlen($sFinalString) > 0) {
						echo '<div class="alert alert-danger">' . $sFinalString . '</div>';
					}
					else {

						$db -> execute('INSERT INTO tournaments(tournamentName, tournamentSummary, tournamentContent, tournamentMode, tournamentSlots, tournamentTeamFormat, tournamentRegistrationEndDate, tournamentStartDate, tournamentRankingPoints) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)', [$sTournamentName, $sTournamentSummary, $sTournamentDescription, $sTournamentMode, $sTournamentSlots, $sTournamentTeamFormat, date('Y-m-d', strtotime($sTournamentRegistrationEndDate)), date('Y-m-d', strtotime($sTournamentStartDate)), $sTournamentRankingPoints]);
						$tournamentId = $db -> fetch('SELECT last_insert_id() AS lastid');

						// print_r([$sTournamentName, $sTournamentSummary, $sTournamentDescription, $sTournamentMode, $sTournamentSlots, $sTournamentTeamFormat, date('Y-m-d', strtotime($sTournamentRegistrationEndDate)), date('Y-m-d', strtotime($sTournamentStartDate)), $sTournamentRankingPoints]);
						Header('Location: ../../tournaments/t/' . $tournamentId['lastid']);
						return;
					}
				}
			?>

			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Create a tournament</h3>
				</div>

				<div class="panel-body">
					<form action="./admin/tournaments/create" method="post">
						<div class="row">
							<div class="col-xs-6">
								<div class="form-group pmd-textfield pmd-textfield-floating-label">
									<label for="tournamentName" class="control-label">Tournament name</label>
									<input id="tournamentName" name="tournamentName" type="text" class="form-control" value="<?php echo isset($_POST['tournamentName']) ? $_POST['tournamentName'] : ''; ?>" />
								</div>
							</div>

							<div class="col-xs-6">
								<div class="form-group pmd-textfield pmd-textfield-floating-label">
									<label for="tournamentMode" class="control-label">Tournament mode</label>
									<select id="tournamentMode" name="tournamentMode" class="select-simple form-control pmd-select2">
										<option value="osu!Standard">osu!Standard</option>
										<option value="osu!Taiko">osu!Taiko</option>
										<option value="osu!Catch">osu!Catch</option>
										<option value="osu!Mania">osu!Mania</option>
									</select>
								</div>
							</div>
						</div>

						<div class="extraSpacing3"></div>

						<div class="row">
							<div class="col-xs-6">
								<div class="form-group pmd-textfield pmd-textfield-floating-label">
									<label for="tournamentSlots" class="control-label">Tournament slots</label>
									<input id="tournamentSlots" name="tournamentSlots" type="number" class="form-control" value="<?php echo isset($_POST['tournamentSlots']) ? $_POST['tournamentSlots'] : ''; ?>" />
								</div>
							</div>

							<div class="col-xs-6">
								<div class="form-group pmd-textfield pmd-textfield-floating-label">
									<label for="tournamentTeamFormat" class="control-label">Tournament slots</label>
									<select id="tournamentTeamFormat" name="tournamentTeamFormat" class="select-simple form-control pmd-select2">
										<option value="1v1" <?php echo ($sTournamentTeamFormat == "1v1") ? "selected" : ""; ?>>1v1</option>
										<option value="2v2" <?php echo ($sTournamentTeamFormat == "2v2") ? "selected" : ""; ?>>2v2</option>
										<option value="3v3" <?php echo ($sTournamentTeamFormat == "3v3") ? "selected" : ""; ?>>3v3</option>
										<option value="4v4" <?php echo ($sTournamentTeamFormat == "4v4") ? "selected" : ""; ?>>4v4</option>
										<option value="5v5" <?php echo ($sTournamentTeamFormat == "5v5") ? "selected" : ""; ?>>5v5</option>
									</select>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-xs-6">
								<div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
									<label class="control-label" for="tournamentRegistrationEndDate">Registration end date</label>
									<input id="tournamentRegistrationEndDate" name="tournamentRegistrationEndDate" type="text" class="form-control" value="<?php echo isset($_POST['tournamentRegistrationEndDate']) ? $_POST['tournamentRegistrationEndDate'] : ''; ?>" />
								</div>
							</div>

							<div class="col-xs-6">
								<div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed">
									<label class="control-label" for="tournamentRegistrationEndDate">Tournament start date</label>
									<input id="tournamentStartDate" name="tournamentStartDate" type="text" class="form-control" value="<?php echo isset($_POST['tournamentStartDate']) ? $_POST['tournamentStartDate'] : ''; ?>" />
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-xs-12">
								<div class="form-group pmd-textfield pmd-textfield-floating-label">
									<label for="tournamentRankingPoints" class="control-label">Tournament ranking points</label>
									<input id="tournamentRankingPoints" name="tournamentRankingPoints" type="number" class="form-control" value="<?php echo isset($_POST['tournamentRankingPoints']) ? $_POST['tournamentRankingPoints'] : ''; ?>" />
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-xs-12">
								<div class="form-group pmd-textfield">
								   <label for="tournamentSummary" class="control-label">Tournament summary</label>
								   <textarea class="form-control" rows="6" name="tournamentSummary"><?php echo isset($_POST['tournamentSummary']) ? $_POST['tournamentSummary'] : '';?></textarea>
								</div>
							</div>
						</div>

						<div class="extraSpacing3"></div>

						<div class="row">
							<div class="col-xs-12">
								<div class="form-group pmd-textfield">
								   <label for="tournamentDescription" class="control-label">Tournament description</label>
								   <textarea class="form-control" rows="6" name="tournamentDescription"><?php echo isset($_POST['tournamentDescription']) ? $_POST['tournamentDescription'] : '';?></textarea>
								</div>
							</div>
						</div>

						<div class="extraSpacing3"></div>

						<div class="row">
							<div class="col-xs-12">
								<button type="submit" class="btn btn-success pull-right"><i class="fa fa-floppy-o"></i> Save</button>
							</div>
						</div>
					</form>
				</div>
			</div>

			<?php
		}
				else if(isset($_GET['p']) && $_GET['p'] == "edit") {
					?>
					<ol class="breadcrumb">
						<li><a href="./">Home</a></li>
						<li><a href="./admin">Adminpanel</a></li>
						<li><a href="./admin/tournaments">Manage tournament</a></li>
						<li class="active">Edit a tournament</li>
					</ol>

					<table id="reports" class="table pmd-table table-hover display responsive nowrap">
						<thead>
							<th>Id</th><th>Name</th><th>Mode</th><th>Format</th><th>Start date</th><th>Ranking points</th>
						</thead>

						<tbody>
							<?php
								$allTournaments = $db -> fetch('SELECT * FROM tournaments', null, true);

								foreach($allTournaments as $tournament) {
									echo '<tr class="clickableRow" data-href="./admin/tournaments/edit/' . $tournament['tournamentId'] . '"><td>' . $tournament['tournamentId'] . '</td><td>' . $tournament['tournamentName'] . '</td><td>' . $tournament['tournamentMode'] . '</td><td>' . $tournament['tournamentTeamFormat'] . '</td><td>' . $tournament['tournamentStartDate']. '</td><td>' . $tournament['tournamentRankingPoints'] . '</td></tr>';
								}
							?>
						</body>
					</table>
					<?php
				}
				else {
			?>
			<ol class="breadcrumb">
				<li><a href="./">Home</a></li>
				<li><a href="./admin">Adminpanel</a></li>
				<li class="active">Manage tournaments</li>
			</ol>

			<div class="alert alert-warning"><i class="fa fa-exclamation-triangle"></i> TODO: Add bb code buttons</div>

			<div class="row">
				<div class="col-xs-6">
					<a href="./admin/tournaments/create" class="thumbnail h4" align="center">
						<h1><i class="fa fa-user"></i></h1>
						<p>Create a tournament</p>
					</a>
				</div>

				<div class="col-xs-6">
					<a href="./admin/tournaments/edit" class="thumbnail h4" align="center">
						<h1><i class="fa fa-trophy" aria-hidden="true"></i></h1>
						<p>Edit a tournament</p>
					</a>
				</div>
			</div>
			<?php } ?>
		</div>

		<?php include_once('./resources/includes/loginpopup.php'); ?>
	</body>

	<script src="js/jquery-3.2.0.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/propeller.min.js"></script>
	<script type="text/javascript" src="http://propeller.in/components/select2/js/select2.full.js"></script>
	<script type="text/javascript" src="http://propeller.in/components/select2/js/pmd-select2.js"></script>
	<script type="text/javascript" language="javascript" src="http://propeller.in/components/datetimepicker/js/moment-with-locales.js"></script>
	<script type="text/javascript" language="javascript" src="http://propeller.in/components/datetimepicker/js/bootstrap-datetimepicker.js"></script>
	<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.13/js/jquery.dataTables.js"></script>

	<script>
		$(".select-simple").select2({
			theme: "bootstrap",
			minimumResultsForSearch: Infinity,
		});

		$('#tournamentRegistrationEndDate, #tournamentStartDate').datetimepicker({'format': "MM/DD/YYYY", 'keepOpen': true});

		$(document).ready(function() {
			$(".clickableRow").click(function() {
		        window.location = $(this).data("href");
		    });

		    $('#reports').DataTable();

			$("#b").on('mousedown', function(evt){
				evt.preventDefault();

				var textarea = document.getElementById("tournamentDescription");

				if($("#tournamentDescription").is(":focus"))
				{
					var select = textarea.value.substring(textarea.selectionStart, textarea.selectionEnd);
					var replace = '[b]' + select + '[/b]';

					textarea.value = textarea.value.substring(0, textarea.selectionStart) + replace + textarea.value.substring(textarea.selectionEnd, textarea.value.length);
				}
			});

			$("#i").on('mousedown', function(evt){
				evt.preventDefault();

				if($("#tournamentDescription").is(":focus"))
				{
					var textarea = document.getElementById("tournamentDescription");
					var select = textarea.value.substring(textarea.selectionStart, textarea.selectionEnd);
					var replace = '[i]' + select + '[/i]';

					textarea.value = textarea.value.substring(0, textarea.selectionStart) + replace + textarea.value.substring(textarea.selectionEnd, textarea.value.length);
				}
			});

			$("#u").on('mousedown', function(evt){
				evt.preventDefault();

				if($("#tournamentDescription").is(":focus"))
				{
					var textarea = document.getElementById("tournamentDescription");
					var select = textarea.value.substring(textarea.selectionStart, textarea.selectionEnd);
					var replace = '[u]' + select + '[/u]';

					textarea.value = textarea.value.substring(0, textarea.selectionStart) + replace + textarea.value.substring(textarea.selectionEnd, textarea.value.length);
				}
			});

			$("#s").on('mousedown', function(evt){
				evt.preventDefault();

				if($("#tournamentDescription").is(":focus"))
				{
					var textarea = document.getElementById("tournamentDescription");
					var select = textarea.value.substring(textarea.selectionStart, textarea.selectionEnd);
					var replace = '[strike]' + select + '[/strike]';

					textarea.value = textarea.value.substring(0, textarea.selectionStart) + replace + textarea.value.substring(textarea.selectionEnd, textarea.value.length);
				}
			});

			$("#heading").on('mousedown', function(evt){
				evt.preventDefault();

				if($("#tournamentDescription").is(":focus"))
				{
					var textarea = document.getElementById("tournamentDescription");
					var select = textarea.value.substring(textarea.selectionStart, textarea.selectionEnd);
					var replace = '[heading]' + select + '[/heading]';

					textarea.value = textarea.value.substring(0, textarea.selectionStart) + replace + textarea.value.substring(textarea.selectionEnd, textarea.value.length);
				}
			});
		});
	</script>
</html>
