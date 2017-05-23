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

		<title>Join team - CustomAllOsu</title>
	</head>

	<body>
		<?php include_once("./resources/includes/banner.php"); ?>
		<?php include_once("./resources/includes/navbar.php"); ?>

		<div class="container">
			<?php
				if(isset($_GET['t'])) {
					if($_SERVER['REQUEST_METHOD'] == 'POST') {
						$sFinalString = '';
						$saturdayCheckboxes = array();
						$sundayCheckboxes = array();

						for($i = 0; $i <= 24; $i ++) {
							if(isset($_POST['sat' . $i]))
								$saturdayCheckboxes['sat' . $i] = 'on';

							if(isset($_POST['sun' . $i]))
								$sundayCheckboxes['sun' . $i] = 'on';
						}

						$saturdayCheckboxes = json_encode($saturdayCheckboxes);
						$sundayCheckboxes = json_encode($sundayCheckboxes);

						// echo '<pre>' , json_encode($saturdayCheckboxes) , '</pre>';

						if(strlen($_POST['textMotivation']) < 3 || strlen($_POST['textMotivation']) >= 1000) {
							$sFinalString .= 'Your motivation has an invalid amount of characters. Minimum characters required are 3 and the maximum is 1000. <br />';
						}

						if(strlen($_POST['joinedTeams']) < 3 || strlen($_POST['joinedTeams']) >= 1000) {
							$sFinalString .= 'Your previously joined teams has an invalid amount of characters. Minimum characters required are 3 and the maximum is 1000. <br />';
						}

						if(strlen($sFinalString) > 0) {
							echo '<div class="alert alert-danger"><b>Something went wrong!</b><br />' . $sFinalString . '</div>';
						}
						else {
							$db -> execute('INSERT INTO teamsignup VALUES(?, ?, ?, ?, ?, ?)', [$_GET['t'], $userData['userId'], $_POST['textMotivation'], $_POST['joinedTeams'], $saturdayCheckboxes, $sundayCheckboxes]);

							Header('Location: ./teams/' . $_GET['t'] . '&signedup');
							return;
						}
					}

					$curTeam = $db -> fetch('SELECT * FROM teams WHERE teamId = ? AND teamRecruiting = 1', [$_GET['t']]);

					if($curTeam) {
						echo '<ol class="breadcrumb">
							<li><a href="./">Home</a></li>
							<li><a href="./teams">All teams</a></li>
							<li><a href="./teams/t/' . $curTeam['teamId'] . '">' . $curTeam['teamName'] . '</a></li>
							<li class="active">Team sign up</li>
						</ol>';
						?>
						<div class="panel panel-primary">
							<div class="panel-heading">
								<h3 class="panel-title">Welcome to the team application form of <?php echo $curTeam['teamName']; ?>!</h3>
							</div>

							<div class="panel-body">
								<form action="./jointeam/<?php echo $curTeam['teamId']; ?>" method="post">
									<div class="row">
										<div class="col-xs-12">
											<div class="form-group pmd-textfield">
											   <label for="textMotivation" class="control-label">Motivation for joining this team:</label>
											   <textarea class="form-control" rows="6" name="textMotivation" style="height: 150px;"><?php echo isset($_POST['textMotivation']) ? $_POST['textMotivation'] : ''; ?></textarea>
											</div>
										</div>
									</div>

									<div class="extraSpacing3"></div>

									<div class="row">
										<div class="col-xs-12">
											<label for="">Availability in weekends in UTC time (Check the boxes you'll be available at):</label><br />
											<b>Saturday:</b>
										</div>
									</div>

									<div class="row">
										<div class="col-xs-12" style="overflow-y: auto;">
											<table class="table">
												<thead>
													<?php for($i = 0; $i <= 24; $i ++) { echo '<th><label for="sat' . $i . '">' . $i . ':00</label></th>';} ?>
												</thead>

												<tbody>
													<?php for($i = 0; $i <= 24; $i ++) { $checked = isset($_POST['sat' . $i]) ? 'checked' : ''; echo '<td><input id="sat' . $i . '" name="sat' . $i . '" type="checkbox" ' . $checked . '/></td>'; } ?>
												</tbody>
											</table>
										</div>
									</div>

									<div class="extraSpacing10"></div>

									<div class="row">
										<div class="col-xs-12"><b>Sunday:</b></div>
									</div>

									<div class="row">
										<div class="col-xs-12" style="overflow-y: auto;">
											<table class="table">
												<thead>
													<?php for($i = 0; $i <= 24; $i ++) { echo '<th><label for="sun' . $i . '">' . $i . ':00</label></th>';} ?>
												</thead>

												<tbody>
													<?php for($i = 0; $i <= 24; $i ++) { $checked = isset($_POST['sun' . $i]) ? 'checked' : ''; echo '<td><input id="sun' . $i . '" name="sun' . $i . '" type="checkbox" ' . $checked . '/></td>'; } ?>
												</tbody>
											</table>
										</div>
									</div>

									<div class="extraSpacing3"></div>

									<div class="row">
										<div class="col-xs-12">
											<div class="form-group pmd-textfield">
											   <label for="joinedTeams" class="control-label">Previously joined teams (if you haven't joined any team yet type "none")</label>
											   <textarea class="form-control" rows="6" name="joinedTeams" style="height: 150px;"><?php echo isset($_POST['joinedTeams']) ? $_POST['joinedTeams'] : ''; ?></textarea>
											</div>
										</div>
									</div>

									<div class="extraSpacing3"></div>

									<div class="row">
										<div class="col-xs-12">
											<button type="submit" class="btn btn-success pull-right">Sign up</button>
										</div>
									</div>
								</form>
							</div>
						</div>

						<?php
					}
					else {
						echo '<ol class="breadcrumb"><li><a href="./">Home</a></li><li><a href="./teams">All teams</a></li><li class="active">Invalid team</li></ol>';
						echo '<div class="alert alert-danger">This team is either invalid or is not recruiting new members. </div>';
					}
				}
				else {
					echo '<ol class="breadcrumb">
						<li><a href="./">Home</a></li>
						<li><a href="./teams">All teams</a></li>
						<li class="active">Invalid team</li>
					</ol>';
				}
			?>
		</div>

		<?php include_once('./resources/includes/loginpopup.php'); ?>
	</body>

	<script src="js/jquery-3.2.0.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/propeller.min.js"></script>
</html>
