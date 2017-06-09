<?php
	require_once 'core/init.php';
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}

	$db = new Db(Config::get('mysql/host'), Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));
	$db -> setFetchMode(PDO::FETCH_ASSOC);
	$userData = User::authenticate(isset($_COOKIE[Config::get('cookie/cookie_name')]) ? $_COOKIE[Config::get('cookie/cookie_name')] : null);
	$serverSettings = $db -> fetch('SELECT * FROM serverSettings');

	$curPage = 'inbox';
?>

<!DOCTYPE html>
<html>
	<head>
		<?php include_once("./resources/includes/meta.php"); ?>
		<?php include_once("./resources/includes/link.php"); ?>

		<title>Inbox - CustomAllOsu</title>
	</head>

	<body>
		<?php include_once("./resources/includes/banner.php"); ?>
		<?php include_once("./resources/includes/navbar.php"); ?>

		<aside id="sidebar">
			<nav class="list-group">
				<a href="./inbox/sendmessage" class="list-group-item">Send a message</a>
				<a href="./inbox" class="list-group-item <?php echo (!isset($_GET['p'])) ? "active" : ""; ?>">Inbox</a>
				<a href="./inbox/tournaments" class="list-group-item <?php echo ($_GET['p'] == "tournaments") ? "active" : ""; ?>">Tournaments</a>
			</nav>
		</aside>

		<div class="content">
			<?php
				if(isset($_SESSION['showMessage'])) {
					echo '<div class="alert alert-success">' . $_SESSION['showMessage'] . '</div>';
					unset($_SESSION['showMessage']);
				}

				if(isset($_GET['a']) && $_GET['a'] == "reply") {
					$thisPm = $db -> fetch('SELECT messageId, messageSenderId, messageReceiverId, username AS messageSenderUsername, messageTitle, messageDateSend, messageContent, messageRead FROM users, messages WHERE users.userId = messages.messageSenderId AND messageReceiverId = ? AND messageId = ?', [$userData['userId'], $_GET['p']]);

					if($thisPm) {
						if($_SERVER['REQUEST_METHOD'] == 'POST') {
							$sFinalString = '';

							if(strlen($_POST['messageTitle']) < 5) {
								$sFinalString .= 'The title needs to contain at least 5 characters. <br />';
							}

							if(strlen($_POST['messageContent']) < 5) {
								$sFinalString .= 'Your message needs to contain at least 5 characters. <br />';
							}

							if(strlen($sFinalString) > 0) {
								echo '<div class="alert alert-danger"><b>Something went wrong!</b><br/>' . $sFinalString . '</div>';
							}
							else {
								// echo '<pre>' , print_r($thisPm) , '</pre>';
								$curDate = date('Y-m-d H:i:s');
								$db -> execute('INSERT INTO messages(messageReceiverId, messageSenderId, messageType, messageTitle, messageDateSend, messageContent, messageRead)
															VALUES(?, ?, ?, ?, ?, ?, ?)', [$thisPm['messageSenderId'], $thisPm['messageReceiverId'], 'PM', $_POST['messageTitle'], $curDate, $_POST['messageContent'], 0]);

								$_SESSION['showMessage'] = 'Succesfully send the message!';
								Header('Location: ../../inbox');
								return;
							}
						}
						?>
							<div class="pmd-card pmd-card-default pmd-z-depth">
								<form action="./inbox/<?php echo $_GET['p']; ?>/reply" method="post">
									<div class="pmd-card-title">
										<div class="media-left">
											<a class="avatar-list-img" href="javascript:void(0);">
												<?php
													foreach(Config::get('config/picture_extension') as $ext) {
														if(file_exists('./resources/avatars/users/' . $thisPm['messageSenderId'] . '.' . $ext)) {
															echo '<img src="./resources/avatars/users/' . $thisPm['messageSenderId'] . '.' . $ext . '" width="40" height="40" />';
														}
													}
												?>

											</a>
										</div>

										<div class="media-body media-middle">
											<h3 class="pmd-card-title-text"><div class="form-group pmd-textfield"><input type="text" class="form-control" name="messageTitle" placeholder="<?php echo $thisPm['messageTitle']; ?>" /></div></h3>
										</div>

										<div class="pmd-card-body">
											<hr>
											<table width="100%">
												<tr>
													<td width="1"><span class="pmd-card-subtitle-text">From:</span></td>
													<td width="1">&emsp;</td>
													<td><a href="./profile/<?php echo $thisPm['messageSenderId']; ?>"><?php echo $thisPm['messageSenderUsername']; ?></a></td>
													<td class="pull-right"><b style="font-size: 20px;"><?php echo date('g.i a', strtotime($thisPm['messageDateSend'])); ?></b></td>
												</tr>
												<tr>
													<td width="1"><span class="pmd-card-subtitle-text">To:</span></td>
													<td width="1">&emsp;</td>
													<td><a href="./profile/<?php echo $thisPm['messageSenderId']; ?>"><?php echo $thisPm['messageSenderUsername']; ?></a></td>
													<td class="pull-right"><span class="pmd-card-subtitle-text"><?php echo date('l, j.m.Y', strtotime($thisPm['messageDateSend'])); ?></span></td>
												</tr>
											</table>
											<hr>
										</div>

										<div class="pmd-card-body">
											<div class="form-group pmd-textfield">
												<label for="messageContent" class="control-label">Message</label>
												<textarea id="messageContent" name="messageContent" class="form-control"><?php echo (isset($_POST['messageContent'])) ? $_POST['messageContent'] : ''; ?></textarea>
											</div>
										</div>

										<div class="pmd-card-actions">
											<button type="submit" class="btn btn-sm pmd-btn-fab pmd-btn-flat pmd-ripple-effect btn-primary pmd-tooltip" data-toggle="tooltip" data-placement="top" title="Send"><i class="material-icons pmd-sm">send</i></button>
										</div>
									</div>
								</form>
							</div>
						<?php
					}
					else {
						echo '<div class="alert alert-danger">Private message could not be found. </div>';
					}
				}
				else if(isset($_GET['a']) && $_GET['a'] == "delete") {
					$thisPm = $db -> fetch('SELECT messageId, messageSenderId, messageReceiverId, username AS messageSenderUsername, messageTitle, messageDateSend, messageContent, messageRead FROM users, messages WHERE users.userId = messages.messageSenderId AND messageReceiverId = ? AND messageId = ?', [$userData['userId'], $_GET['p']]);

					if($thisPm) {
						$_SESSION['showMessage'] = 'Succesfully deleted the message.';
						$db -> execute('DELETE FROM messages WHERE messageId = ?', [$thisPm['messageId']]);
						Header('Location: ../../inbox');
					}
					else {
						echo '<div class="alert alert-danger">Private message could not be found. </div>';
					}
				}
				else {
					if(!isset($_GET['p'])) {
						$allPM = $db -> fetch('SELECT messageId, messageSenderId, username AS messageSenderUsername, messageTitle, messageDateSend, messageContent, messageRead FROM users, messages WHERE users.userId = messages.messageSenderId AND messageReceiverId = ?', [$userData['userId']], true);

						if(!$allPM) {
							echo '<div class="alert alert-info">You have no messages.</div>';
						}

						foreach($allPM as $pm) {
							echo '<a href="./inbox/' . $pm['messageId'] . '" style="color: black;">
									<div class="pmd-z-depth inboxTile">
										<h3 class="pmd-card-title-text">
											<b><i class="material-icons md-dark pmd-xs" style="vertical-align: bottom;">person</i> ' . $pm['messageSenderUsername'] . ' <span class="pull-right">' . date('d/m/Y', strtotime($pm['messageDateSend'])) . '</span></b> <br />
											<span class="pmd-card-subtitle-text pull-right">' . date('g.i a', strtotime($pm['messageDateSend'])) . '</span>
										</h3>

										<span class="pmd-card-subtitle-text">' . $pm['messageTitle'] . '</span>
									</div>
								</a>';
						}
					}
					else if($_GET['p'] == "tournaments"){
						echo 'hi';
					}
					else {
						$thisPm = $db -> fetch('SELECT messageId, messageSenderId, username AS messageSenderUsername, messageTitle, messageDateSend, messageContent, messageRead FROM users, messages WHERE users.userId = messages.messageSenderId AND messageReceiverId = ? AND messageId = ?', [$userData['userId'], $_GET['p']]);

						if($thisPm) {
							?>
								<div class="pmd-card pmd-card-default pmd-z-depth">
									<div class="pmd-card-title">
										<div class="media-left">
										    <a class="avatar-list-img" href="javascript:void(0);">
												<?php
													foreach(Config::get('config/picture_extension') as $ext) {
														if(file_exists('./resources/avatars/users/' . $thisPm['messageSenderId'] . '.' . $ext)) {
															echo '<img src="./resources/avatars/users/' . $thisPm['messageSenderId'] . '.' . $ext . '" width="40" height="40" />';
														}
													}
												?>

										    </a>
										</div>

										<div class="media-body media-middle">
								            <h3 class="pmd-card-title-text">| <b><?php echo $thisPm['messageTitle']; ?></b></h3>
								        </div>

										<div class="pmd-card-body">
											<hr>
											<table width="100%">
												<tr>
													<td width="1"><span class="pmd-card-subtitle-text">From:</span></td>
													<td width="1">&emsp;</td>
													<td><a href="./profile/<?php echo $thisPm['messageSenderId']; ?>"><?php echo $thisPm['messageSenderUsername']; ?></a></td>
													<td class="pull-right"><b style="font-size: 20px;"><?php echo date('g.i a', strtotime($thisPm['messageDateSend'])); ?></b></td>
												</tr>
												<tr>
													<td width="1"><span class="pmd-card-subtitle-text">To:</span></td>
													<td width="1">&emsp;</td>
													<td><a href="./profile/<?php echo $userData['userId']; ?>"><?php echo $userData['username']; ?></a></td>
													<td class="pull-right"><span class="pmd-card-subtitle-text"><?php echo date('l, j.m.Y', strtotime($thisPm['messageDateSend'])); ?></span></td>
												</tr>
											</table>
											<hr>
										</div>

										<div class="pmd-card-body">
									        <?php echo $thisPm['messageContent']; ?>
									    </div>

										<div class="pmd-card-actions">
									        <a href="./inbox/<?php echo $_GET['p']; ?>/reply" class="btn btn-sm pmd-btn-fab pmd-btn-flat pmd-ripple-effect btn-primary pmd-tooltip" data-toggle="tooltip" data-placement="top" title="Reply"><i class="material-icons pmd-sm">reply</i></a>
									        <a href="./inbox/<?php echo $_GET['p']; ?>/delete" class="btn btn-sm pmd-btn-fab pmd-btn-flat pmd-ripple-effect btn-primary pmd-tooltip" data-toggle="tooltip" data-placement="top" title="Delete"><i class="material-icons pmd-sm">delete</i></a>
											<a href="./report/<?php echo $_GET['p']; ?>" class="btn btn-sm pmd-btn-fab pmd-btn-flat pmd-ripple-effect btn-primary pmd-tooltip" data-toggle="tooltip" data-placement="top" title="Report"><i class="material-icons pmd-sm">report</i></a>
									    </div>
									</div>
								</div>
							<?php
						}
						else {
							echo '<div class="alert alert-danger">Private message could not be found. </div>';
						}
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
		$(".clickableRow").click(function() {
			window.location = $(this).data("href");
		});

		$('[data-toggle="tooltip"]').tooltip()
	</script>
</html>
