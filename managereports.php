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

	$curPage = 'admin';
?>

<!DOCTYPE html>
<html>
	<head>
		<?php include_once("./resources/includes/meta.php"); ?>
		<?php include_once("./resources/includes/link.php"); ?>
		<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.13/css/jquery.dataTables.css">

		<title>Manage reports - CustomAllOsu</title>
	</head>

	<body>
		<?php include_once("./resources/includes/banner.php"); ?>
		<?php include_once("./resources/includes/navbar.php"); ?>

		<div class="container">
			<ol class="breadcrumb">
				<li><a href="./">Home</a></li>
				<li><a href="./admin">Admin panel</a></li>
				<li class="active">Reports</li>
			</ol>

			<table id="reports" class="table pmd-table table-hover display responsive nowrap">
				<thead>
					<th>Id</th><th>Type</th><th>Submitted by</th>
				</thead>

				<tbody>
					<?php
						$allReports = $db -> fetch('SELECT * FROM reports', null, true);

						foreach($allReports as $report) {
							echo '<tr class="clickableRow" data-href="./report/' . $report['reportId'] . '"><td>' . $report['reportId'] . '</td><td>' . $report['reportType'] . '</td><td>' . $report['reportExplanation'] . '</td></tr>';
						}
					?>
				</tbody>
			</table>
		</div>

		<?php include_once('./resources/includes/loginpopup.php'); ?>
	</body>

	<script src="js/jquery-3.2.0.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/propeller.min.js"></script>
	<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.13/js/jquery.dataTables.js"></script>

	<script>
		$(document).ready(function() {
			$(".clickableRow").click(function() {
		        window.location = $(this).data("href");
		    });

		    $('#reports').DataTable();
		});
	</script>
</html>
