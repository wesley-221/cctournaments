<?php
	require_once 'core/init.php';
	require_once 'resources/includes/bbparser/stringparser_bbcode.class.php';
	require_once 'resources/includes/bbcode.php';

	$db = new Db(Config::get('mysql/host'), Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));
	$db -> setFetchMode(PDO::FETCH_ASSOC);
	$userData = User::authenticate(isset($_COOKIE[Config::get('cookie/cookie_name')]) ? $_COOKIE[Config::get('cookie/cookie_name')] : null);
	$serverSettings = $db -> fetch('SELECT * FROM serverSettings');

	$curPage = 'news';
?>

<!DOCTYPE html>
<html>
	<head>
		<?php include_once("./resources/includes/meta.php"); ?>
		<?php include_once("./resources/includes/link.php"); ?>

		<title>News - CustomAllOsu</title>
	</head>

	<body>
		<?php include_once("./resources/includes/banner.php"); ?>
		<?php include_once("./resources/includes/navbar.php"); ?>

		<div class="container">
			<?php
				if(!isset($_GET['article'])) {
			?>

			<ol class="breadcrumb">
				<li><a href="./">Home</a></li>
				<li class="active">News</li>
			</ol>

			<div class="panel panel-primary news">
				<div class="panel-heading">
					<h3 class="panel-title">News</h3>
				</div>

				<div class="panel-body">
					<?php
						$newsArticles = $db -> fetch('SELECT * FROM news ORDER BY newsId DESC', null, true);

						foreach($newsArticles as $newsArticle) {
							echo '<a style="text-decoration: none;" href="./news/' . $newsArticle["newsId"] . '"><div class="newsarticle"><div class="newstitle"><b>' . $newsArticle["newsTitle"] . ' - ' . date('d/m/Y', strtotime($newsArticle["newsPostDate"])) . '</b></div><div class="newscontent">' . $newsArticle["newsDescription"] . '</div></div></a>';
							echo '<div class="extraSpacing3"></div>';
						}
					?>

					<center>
						<ul class="pagination">
							<li>
								<a href="#" aria-label="Previous">
									<span aria-hidden="true">&laquo;</span>
								</a>
							</li>
							<li class="active"><a href="#">1</a></li>
							<li><a href="#">2</a></li>
							<li><a href="#">3</a></li>
							<li><a href="#">4</a></li>
							<li><a href="#">5</a></li>
							<li>
								<a href="#" aria-label="Next">
									<span aria-hidden="true">&raquo;</span>
								</a>
							</li>
						</ul>
					</center>
				</div>
			</div>

			<?php
				}
				else {
					$newsArticle = $db -> fetch('SELECT * FROM news WHERE newsId = ?', [$_GET['article']]);

					echo '<ol class="breadcrumb"><li><a href="./">Home</a></li><li><a href="./news">News</a></li><li class="active">' . $newsArticle["newsTitle"] . '</li></ol>';

					if(isset($_GET['editSuccess'])) {
						echo '<div class="alert alert-success">Succesfully changed the article!</div>';
					}

					echo '<div class="panel panel-primary"><div class="panel-body"><h3 class="fullnewsTitle">' . $newsArticle["newsTitle"] . '</h3><div class="fullnewsContent">' . $bbcode->parse($newsArticle["newsContent"]	) . '</div></div></div>';
				}
			?>
		</div>

		<?php include_once('./resources/includes/loginpopup.php'); ?>
	</body>

	<script src="js/jquery-3.2.0.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/propeller.min.js"></script>
</html>
