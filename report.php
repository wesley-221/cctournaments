<?php
	require_once 'core/init.php';
	require_once 'resources/includes/bbparser/stringparser_bbcode.class.php';
	require_once 'resources/includes/bbcode.php';

	$db = new Db(Config::get('mysql/host'), Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));
	$db -> setFetchMode(PDO::FETCH_ASSOC);
	$userData = User::authenticate(isset($_COOKIE[Config::get('cookie/cookie_name')]) ? $_COOKIE[Config::get('cookie/cookie_name')] : null);
	$serverSettings = $db -> fetch('SELECT * FROM serverSettings');

	$reportType = isset($_POST['reportType']) ? $_POST['reportType'] : '';
	$reportExplanation = isset($_POST['reportExplanation']) ? $_POST['reportExplanation'] : '';

?>

<!DOCTYPE html>
<html>
	<head>
		<?php include_once("./resources/includes/meta.php"); ?>
		<?php include_once("./resources/includes/link.php"); ?>

		<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
		<link href="http://propeller.in/components/select2/css/select2-bootstrap.css" rel="stylesheet" />
		<link href="http://propeller.in/components/select2/css/pmd-select2.css" rel="stylesheet" />

		<title>Report - CustomAllOsu</title>
	</head>

	<body>
		<?php include_once("./resources/includes/banner.php"); ?>
		<?php include_once("./resources/includes/navbar.php"); ?>

		<div class="container">
			<?php
				if(isset($_GET['r'])) {
					if($userData['permissionId'] <= 2) {
						echo '<div class="alert alert-danger">You are not eligible to view this page.</div>';
					}
					else {
					?>
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title">Report</h3>
						</div>

						<div class="panel-body">
							<?php
								$report = $db -> fetch('SELECT reports.userId, username, reportType, reportExplanation FROM reports, users WHERE reports.userId = users.userId AND reportId = ?', [$_GET['r']]);
							?>

							<div>
								<h3 class="textareaLabel">Report type</h3>

								<?php echo $report['reportType']; ?>
							</div>

							<div>
								<h3 class="textareaLabel">Report explanation</h3>
								<?php echo $bbcode->parse($report['reportExplanation']); ?>
							</div>
						</div>
					</div>
					<?php
					}
				}
				else {
					if($_SERVER['REQUEST_METHOD'] == 'POST') {
						$sFinalString = '';
						if(!strcmp($reportType, 'bug') || !strcmp($reportType, 'user') || !strcmp($reportType, 'team')){}else {
							$sFinalString .= 'Invalid bug type. Please select a valid one. <br />';
						}

						if(strlen($reportExplanation) <= 3) {
							$sFinalString .= 'The explanation needs to contain at least 3 characters. <br>';
						}

						if(strlen($sFinalString) > 0) {
							echo '<div class="alert alert-danger">' . $sFinalString . '</div>';
						}
						else {
							$db -> execute('INSERT INTO reports(userId, reportType, reportExplanation) VALUES(?, ?, ?)', [$userData['userId'], $reportType, $reportExplanation]);
							$lastId = $db -> fetch('SELECT last_insert_id() AS lastId');
							Header('Location: ./report/' . $lastId['lastId']);
						}
					}
					?>
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title">Report</h3>
						</div>

						<div class="panel-body">
							<form action="./report" method="post">
								<div class="form-group pmd-textfield">
									<label for="userPermission" class="control-label">Choose what you want to report</label>
									<select name="reportType" class="select-simple form-control pmd-select2">
										<option value="bug" <?php echo ($reportType === 'bug') ? 'selected' : ''; ?>>Report a bug</option>
										<option value="user" <?php echo ($reportType === 'user') ? 'selected' : ''; ?>>Report a user</option>
										<option value="team" <?php echo ($reportType === 'team') ? 'selected' : ''; ?>>Report a team</option>
									</select>
								</div>

								<div class="form-group pmd-textfield">
								   <label for="bugExplanation" class="control-label">Explanation of the report</label>
								   <textarea class="form-control" rows="6" name="reportExplanation" style="height: 300px;"><?php echo $reportExplanation; ?></textarea>
								</div>

								<button type="submit" class="btn btn-success pull-right">Submit</button>
							</form>
						</div>
					</div>
					<?php
				}
			?>

		</div>

		<?php include_once('./resources/includes/loginpopup.php'); ?>
	</body>

	<script src="js/jquery-3.2.0.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/propeller.min.js"></script>
	<script type="text/javascript" src="http://propeller.in/components/select2/js/select2.full.js"></script>
	<script type="text/javascript" src="http://propeller.in/components/select2/js/pmd-select2.js"></script>
	<script>
		$(document).ready(function() {
			$(".select-simple").select2({
				theme: "bootstrap",
				minimumResultsForSearch: Infinity,
			});
		});
	</script>
</html>
