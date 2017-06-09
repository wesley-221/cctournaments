<?php
	require_once 'core/init.php';

	$db = new Db(Config::get('mysql/host'), Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));
	$db -> setFetchMode(PDO::FETCH_ASSOC);
	$userData = User::authenticate(isset($_COOKIE[Config::get('cookie/cookie_name')]) ? $_COOKIE[Config::get('cookie/cookie_name')] : null);
	$serverSettings = $db -> fetch('SELECT * FROM serverSettings');

	$curPage = 'todo';
?>

<!DOCTYPE html>
<html>
	<head>
		<?php include_once("./resources/includes/meta.php"); ?>
		<?php include_once("./resources/includes/link.php"); ?>

		<title>Todo - CustomAllOsu</title>
	</head>

	<body>
		<?php include_once("./resources/includes/banner.php"); ?>
		<?php include_once("./resources/includes/navbar.php"); ?>

		<div class="container">
			<?php
				if($userData['permissionId'] >= 3) {
					if($_SERVER['REQUEST_METHOD'] == 'POST') {
						if(strlen($_POST['todoEntry']) > 5) {
							$db -> execute('INSERT INTO todo(todoCommitUserId, todoContent) VALUES(?, ?)', [$userData['userId'], $_POST['todoEntry']]);
							Header('Location: ./todo');
						}
						else {
							echo '<div class="alert alert-danger">Entry has to contain at least 5 characters.</div>';
						}
					}
					?>
						<div class="panel-group pmd-accordion">
							<div class="panel panel-warning">
								<div class="panel-heading" role="tab" id="headingOne">
									<h4 class="panel-title">
										<a data-toggle="collapse" href="#collapseMe">Commit a new Todo entry</a>
									</h4>
								</div>

								<div id="collapseMe" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
									<div class="panel-body">
										<form action="./todo" method="post">
											<div class="form-group pmd-textfield pmd-textfield-floating-label">
												<label for="todoEntry" class="control-label">Todo Entry</label>
												<input type="text" id="todoEntry" name="todoEntry" class="form-control">
											</div>

											<button type="submit" class="btn btn-success pull-right">Commit</button>
										</form>
									</div>
								</div>
							</div>
						</div>
					<?php
				}
			?>

			<?php
				$allTodos = $db -> fetch('SELECT todoId, todo.todoCommitUserId, username, todoContent FROM todo, users WHERE users.userId = todo.todoCommitUserId ORDER BY todoId DESC', null, true);

				$curElement = 1;

				foreach($allTodos as $todo) {
					if($curElement == 1) {
						echo '<div class="row">';
					}
					foreach(Config::get('config/picture_extension') as $ext) {
						if(file_exists('./resources/avatars/users/' . $todo['todoCommitUserId'] . '.' . $ext)) {
							$avatar = '<img src="./resources/avatars/users/' . $todo['todoCommitUserId'] . '.' . $ext . '" width="80" height="80" />';
						}
					}

					echo '<div class="col-xs-4">
						<div class="pmd-card pmd-card-media-inline pmd-card-default pmd-z-depth pmd-card-inverse">
							<div class="pmd-card-media">
								<div class="media-body">
									<h2 class="pmd-card-title-text">' . $todo['username'] . '</h2>
									<span class="pmd-card-subtitle-text">' . $todo['todoContent'] . '</span>
								</div>

								<div class="media-right media-middle">
									<a href="./profile/' . $todo['todoCommitUserId'] . '">' . $avatar . '</a>
								</div>
							</div>';

					if($userData['permissionId'] >= 3) {
						echo '<div class="pmd-card-actions">
							<button id="btnCheck" class="btn pmd-btn-fab pmd-btn-raised pmd-ripple-effect btn-success btn-sm" type="button"><i class="material-icons pmd-sm" data-id="' . $todo['todoId'] . '">check</i></button>
							<button class="btn pmd-btn-fab pmd-btn-raised pmd-ripple-effect btn-danger btn-sm" type="button"><i class="material-icons pmd-sm" data-id="' . $todo['todoId'] . '">close</i></button>
						</div>';
					}

					echo '</div>
					</div>';

					if($curElement == 3) {
						echo '</div>';
						$curElement = 1;
					}
					else {
						$curElement ++;
					}
				}
			?>
		</div>

		<?php include_once('./resources/includes/loginpopup.php'); ?>
	</body>

	<script src="js/jquery-3.2.0.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/propeller.min.js"></script>
	<script>
		$("#btnCheck").on('click', function(){
			$.ajax({
				url: './todo_ajax.php',
				data: {
					action: '',
				},
				type: 'post'
			});
		});
	</script>
</html>
