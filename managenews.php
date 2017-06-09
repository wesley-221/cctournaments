<?php
	require_once 'core/init.php';

	$db = new Db(Config::get('mysql/host'), Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));
	$db -> setFetchMode(PDO::FETCH_ASSOC);
	$userData = User::authenticate(isset($_COOKIE[Config::get('cookie/cookie_name')]) ? $_COOKIE[Config::get('cookie/cookie_name')] : null);
	$serverSettings = $db -> fetch('SELECT * FROM serverSettings');

	$sNewsTitle = isset($_POST['newsTitle']) ? $_POST['newsTitle'] : '';
	$sNewsDescription = isset($_POST['newsDescription']) ? $_POST['newsDescription'] : '';
	$sNewsContent = isset($_POST['newsContent']) ? $_POST['newsContent'] : '';

	if($userData['loggedin'] != 1 || $userData['permissionId'] <= 2) {
		Header('Location: ./');
		return;
	}

	$curPage = 'admin';

	// if($_SERVER['REQUEST_METHOD'] == 'POST') {
	// 	if(isset($_GET['p']) && $_GET['p'] == "edit") {
	// 		echo 'hi';
	// 	}
	// }
?>

<!DOCTYPE html>
<html>
	<head>
		<?php include_once("./resources/includes/meta.php"); ?>
		<?php include_once("./resources/includes/link.php"); ?>

		<title>Manage news - CustomAllOsu</title>
	</head>

	<body>
		<?php include_once("./resources/includes/banner.php"); ?>
		<?php include_once("./resources/includes/navbar.php"); ?>

		<div class="container">
			<?php if(isset($_GET['p']) && $_GET['p'] == "create") { ?>
			<ol class="breadcrumb">
				<li><a href="./">Home</a></li>
				<li><a href="./admin">Adminpanel</a></li>
				<li><a href="./admin/news">Manage News</a></li>
				<li class="active">Create a news article</li>
			</ol>

			<?php
				if($_SERVER['REQUEST_METHOD'] == 'POST') {
					if(isset($_GET['p']) && $_GET['p'] == "create") {
						$sFinalString = '';

						if(strlen($sNewsTitle) < 3 || strlen($sNewsTitle) >= 100) {
							$sFinalString .= "The news title needs to contain at least 3 characters and a maximum of 100. <br>";
						}

						if(strlen($sNewsDescription) < 3 || strlen($sNewsDescription) >= 100) {
							$sFinalString .= "The news description needs to contain at least 3 characters and a maximum of 100. <br>";
						}

						if(strlen($sNewsContent) < 3) {
							$sFinalString .= "The news content needs to contain at least 3 characters. <br>";
						}

						if(strlen($sFinalString) > 0) {
							echo '<div class="alert alert-danger" role="alert"><b>Something went wrong!</b><br>' . $sFinalString . '</div>';
						}
						else {
							$dtNewsPostDate = date('Y-m-d');
							$db -> execute('INSERT INTO news(newsTitle, newsPostDate, newsDescription, newsContent) VALUES(?, ?, ?, ?)', [$sNewsTitle, $dtNewsPostDate, $sNewsDescription, $sNewsContent]);

							$lastId = $db -> fetch('SELECT newsId FROM news WHERE newsTitle = ? AND newsPostDate = ? AND newsDescription = ? AND newsContent = ? LIMIT 1', [$sNewsTitle, $dtNewsPostDate, $sNewsDescription, $sNewsContent]);

							header('Location: ./news/' . $lastId["newsId"]);
							return;
						}
					}
				}
			?>

			<div class="panel panel-primary news">
				<div class="panel-heading">
					<h3 class="panel-title">Create a news article</h3>
				</div>

				<form action="./admin/news/create" method="post">
					<div class="panel-body">
						<div class="row">
							<div class="col-xs-12">
								<div class="form-group pmd-textfield pmd-textfield-floating-label">
									<label for="newsTitle" class="control-label">News title</label>
									<input id="newsTitle" name="newsTitle" type="text" class="form-control" value="<?php echo $sNewsTitle; ?>" />
								</div>
							</div>
						</div>

						<div class="extraSpacing3"></div>

						<div class="row">
							<div class="col-xs-12">
								<div class="form-group pmd-textfield pmd-textfield-floating-label">
									<label for="newsDescription" class="control-label">News description</label>
									<input id="newsDescription" name="newsDescription" type="text" class="form-control" value="<?php echo $sNewsDescription; ?>" />
								</div>
							</div>
						</div>

						<div class="extraSpacing3"></div>

						<div class="row">
							<div class="col-xs-12">
								<div class="form-group pmd-textfield">
								   <label for="newsContent" class="control-label">News content</label>
								   <textarea class="form-control" rows="6" name="newsContent" style="height: 350px;"><?php echo $sNewsContent; ?></textarea>
								</div>
							</div>
						</div>

						<div class="extraSpacing10"></div>

						<div class="row">
							<div class="col-xs-12"><button type="submit" class="btn btn-success pull-right"><i class="fa fa-floppy-o"></i> Save</button></div>
						</div>
					</div>
				</form>
			</div>
			<?php
				}
				else if(isset($_GET['p']) && $_GET['p'] == "edit") {
					if(isset($_GET['article'])) {
						$temp = $db -> fetch('SELECT newsTitle FROM news WHERE newsId = ?', [$_GET['article']]);

						if($temp) {
							echo '<ol class="breadcrumb">
								<li><a href="./">Home</a></li>
								<li><a href="./admin">Adminpanel</a></li>
								<li><a href="./admin/news">Manage News</a></li>
								<li><a href="./admin/news/edit">Edit a news article</a></li>
								<li class="active">' . $temp['newsTitle'] . '</li>
							</ol>';
						}
						else {
							echo '<ol class="breadcrumb">
								<li><a href="./">Home</a></li>
								<li><a href="./admin">Adminpanel</a></li>
								<li><a href="./admin/news">Manage News</a></li>
								<li class="active">Edit a news article</li>
							</ol>';
						}
					}
					else {
						echo '<ol class="breadcrumb">
							<li><a href="./">Home</a></li>
							<li><a href="./admin">Adminpanel</a></li>
							<li><a href="./admin/news">Manage News</a></li>
							<li class="active">Edit a news article</li>
						</ol>';
					}

					if($_SERVER['REQUEST_METHOD'] == 'POST') {
						$sFinalString = '';

						if(strlen($sNewsTitle) < 3 || strlen($sNewsTitle) >= 100) {
							$sFinalString .= "The news title needs to contain at least 3 characters and a maximum of 100. <br>";
						}

						if(strlen($sNewsDescription) < 3 || strlen($sNewsDescription) >= 100) {
							$sFinalString .= "The news description needs to contain at least 3 characters and a maximum of 100. <br>";
						}

						if(strlen($sNewsContent) <= 5) {
							$sFinalString .= "The news content needs to contain at least 3 characters. <br>";
						}

						if(strlen($sFinalString) > 0) {
							echo '<div class="alert alert-danger" role="alert"><b>Something went wrong!</b><br>' . $sFinalString . '</div>';
						}
						else {
							$dtNewsPostDate = date('Y-m-d');
							$db -> execute('UPDATE news SET newsTitle = ?, newsDescription = ?, newsContent = ? WHERE newsId = ?', [$sNewsTitle, $sNewsDescription, $sNewsContent, $_GET['article']]);

							$lastId = $db -> fetch('SELECT newsId FROM news WHERE newsTitle = ? AND newsPostDate = ? AND newsDescription = ? AND newsContent = ? LIMIT 1', [$sNewsTitle, $dtNewsPostDate, $sNewsDescription, $sNewsContent]);

							header('Location: ./news/' . $_GET['article'] . '&editSuccess');
							return;
						}
					}
			?>


			<div class="panel panel-primary news">
				<div class="panel-heading">
					<h3 class="panel-title">Edit a news article</h3>
				</div>

				<div class="panel-body">
					<?php
						if(!isset($_GET['article'])) {
							$allNews = $db -> fetch('SELECT newsId, newsTitle, newsPostDate FROM news', null, true);

							echo '<table class="table table-striped"><thead><th>News id</th><th>News title</th><th>Post date</th><th>Action</th></thead>';
							foreach($allNews as $news) {
								echo '<tr><td>' . $news['newsId'] . '</td><td>' . $news['newsTitle'] . '</td><td>' . $news['newsPostDate'] . '</td><td><a href="./admin/news/edit/' . $news['newsId'] . '" class="btn btn-primary"><i class="fa fa-pencil-square-o"></i> Edit</a> <a href="./admin/news/edit/' . $news['newsId'] . '&delete" class="btn btn-primary"><i class="fa fa-trash"></i> Delete</a></td></tr>';
							}
							echo '</table>';
						}
						else {
							$curArticle = $db -> fetch('SELECT * FROM news WHERE newsId = ?', [$_GET['article']]);

							if($curArticle) {
							?>
							<form action="./admin/news/edit/<?php echo $_GET['article']; ?>" method="post">
								<div class="panel-body">
									<div class="row">
										<div class="col-xs-12">
											<div class="form-group pmd-textfield pmd-textfield-floating-label">
												<label for="newsTitle" class="control-label">News title</label>
												<input id="newsTitle" name="newsTitle" type="text" class="form-control" value="<?php echo isset($_POST['newsTitle']) ? $_POST['newsTitle'] : $curArticle['newsTitle']; ?>" />
											</div>
										</div>
									</div>

									<div class="extraSpacing3"></div>

									<div class="row">
										<div class="col-xs-12">
											<div class="form-group pmd-textfield pmd-textfield-floating-label">
												<label for="newsDescription" class="control-label">News description</label>
												<input id="newsDescription" name="newsDescription" type="text" class="form-control" value="<?php echo isset($_POST['newsDescription']) ? $_POST['newsDescription'] : $curArticle['newsDescription']; ?>"/>
											</div>
										</div>
									</div>

									<div class="extraSpacing3"></div>

									<div class="row">
										<div class="col-xs-12">
											<div class="form-group pmd-textfield">
											   <label for="newsContent" class="control-label">News content</label>
											   <textarea class="form-control" rows="6" name="newsContent" style="height: 350px;"><?php echo isset($_POST['newsContent']) ? $_POST['newsContent'] : $curArticle['newsContent']; ?></textarea>
											</div>
										</div>
									</div>

									<div class="extraSpacing10"></div>

									<div class="row">
										<div class="col-xs-12"><button type="submit" class="btn btn-success pull-right"><i class="fa fa-floppy-o"></i> Save</button></div>
									</div>
								</div>
							</form>

							<?php }
							else {
								echo '<div class="alert alert-danger">This article was not found. <a href="./admin/news/edit">Please select a valid article by clicking here.</a></div>';
							}
						}
					?>
				</div>
			</div>
			<?php } else { ?>
			<ol class="breadcrumb">
				<li><a href="./">Home</a></li>
				<li><a href="./admin">Adminpanel</a></li>
				<li class="active">Manage News</li>
			</ol>

			<div class="alert alert-warning"><i class="fa fa-exclamation-triangle"></i> TODO: Add buttons for BB code</div>

			<div class="row">
				<div class="col-xs-6">
					<a href="./admin/news/create" class="thumbnail h4" align="center">
						<h1><i class="fa fa-file-text"></i></h1>
						<p>Create a news article</p>
					</a>
				</div>

				<div class="col-xs-6">
					<a href="./admin/news/edit" class="thumbnail h4" align="center">
						<h1><i class="fa fa-pencil-square-o"></i></h1>
						<p>Edit a news article</p>
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

	<script>
		$(document).ready(function() {
			$("#b").on('mousedown', function(evt){
				evt.preventDefault();

				var textarea = document.getElementById("newsContent");

				if($("#newsContent").is(":focus"))
				{
					var select = textarea.value.substring(textarea.selectionStart, textarea.selectionEnd);
					var replace = '[b]' + select + '[/b]';

					textarea.value = textarea.value.substring(0, textarea.selectionStart) + replace + textarea.value.substring(textarea.selectionEnd, textarea.value.length);
				}
			});

			$("#i").on('mousedown', function(evt){
				evt.preventDefault();

				if($("#newsContent").is(":focus"))
				{
					var textarea = document.getElementById("newsContent");
					var select = textarea.value.substring(textarea.selectionStart, textarea.selectionEnd);
					var replace = '[i]' + select + '[/i]';

					textarea.value = textarea.value.substring(0, textarea.selectionStart) + replace + textarea.value.substring(textarea.selectionEnd, textarea.value.length);
				}
			});

			$("#u").on('mousedown', function(evt){
				evt.preventDefault();

				if($("#newsContent").is(":focus"))
				{
					var textarea = document.getElementById("newsContent");
					var select = textarea.value.substring(textarea.selectionStart, textarea.selectionEnd);
					var replace = '[u]' + select + '[/u]';

					textarea.value = textarea.value.substring(0, textarea.selectionStart) + replace + textarea.value.substring(textarea.selectionEnd, textarea.value.length);
				}
			});

			$("#s").on('mousedown', function(evt){
				evt.preventDefault();

				if($("#newsContent").is(":focus"))
				{
					var textarea = document.getElementById("newsContent");
					var select = textarea.value.substring(textarea.selectionStart, textarea.selectionEnd);
					var replace = '[strike]' + select + '[/strike]';

					textarea.value = textarea.value.substring(0, textarea.selectionStart) + replace + textarea.value.substring(textarea.selectionEnd, textarea.value.length);
				}
			});

			$("#heading").on('mousedown', function(evt){
				evt.preventDefault();

				if($("#newsContent").is(":focus"))
				{
					var textarea = document.getElementById("newsContent");
					var select = textarea.value.substring(textarea.selectionStart, textarea.selectionEnd);
					var replace = '[heading]' + select + '[/heading]';

					textarea.value = textarea.value.substring(0, textarea.selectionStart) + replace + textarea.value.substring(textarea.selectionEnd, textarea.value.length);
				}
			});
		});
	</script>
</html>
