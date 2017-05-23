<?php
	require_once 'core/init.php';
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}

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

		<title>Send a message - CustomAllOsu</title>
	</head>

	<body>
		<?php include_once("./resources/includes/banner.php"); ?>
		<?php include_once("./resources/includes/navbar.php"); ?>

		<aside id="sidebar">
			<nav class="list-group">
				<a href="./inbox/sendmessage" class="list-group-item active">Send a message</a>
				<a href="./inbox" class="list-group-item">Inbox</a>
				<a href="./inbox/tournaments" class="list-group-item">Tournaments</a>
			</nav>
		</aside>

		<div class="content">
			<?php
				if($_SERVER['REQUEST_METHOD'] == 'POST') {
					$sFinalString = '';

					if(strlen($_POST['messageTitle']) < 5) {
						$sFinalString .= 'The title needs to contain at least 5 characters. <br />';
					}

					if(strpos($_POST['messageReceiver'], ',')) {
						$allUsers = explode(',', $_POST['messageReceiver']);

						function trim_value(&$value) {
						    $value = trim($value);
						}
						array_walk($allUsers, 'trim_value');

						foreach($allUsers as $user) {
							$receiverUser = $db -> fetch('SELECT userId, username FROM users WHERE username = ? LIMIT 1', [$user]);

							if(!$receiverUser) {
								$sFinalString .= 'The user "' . $user . '" was not found. <br />';
							}
						}
					}
					else {
						$receiverUser = $db -> fetch('SELECT userId, username FROM users WHERE username = ? LIMIT 1', [$_POST['messageReceiver']]);

						if(!$receiverUser) {
							$sFinalString .= 'The user "' . $_POST['messageReceiver'] . '" was not found. <br />';
						}
					}

					if(strlen($_POST['messageContent']) < 5) {
						$sFinalString .= 'Your message needs to contain at least 5 characters. <br />';
					}

					if(strlen($sFinalString) > 0) {
						echo '<div class="alert alert-danger"><b>Something went wrong!</b> <br />' . $sFinalString . '</div>';
					}
					else {
						// TODO: send a message to a single user instead of all users at the same time
						
						foreach($allUsers as $user) {
							$senderUserId = $db -> fetch('SELECT userId FROM users WHERE username = ?', [$user]);
							$curDate = date('Y-m-d H:i:s');
							$db -> execute('INSERT INTO messages(messageReceiverId, messageSenderId, messageType, messageTitle, messageDateSend, messageContent, messageRead)
														VALUES(?, ?, ?, ?, ?, ?, ?)', [$senderUserId['userId'], $userData['userId'], 'PM', $_POST['messageTitle'], $curDate, $_POST['messageContent'], 0]);
						}

						$_SESSION['showMessage'] = 'Succesfully send the message to ' . implode(', ', $allUsers) . '.';
						header('Location: ../inbox');
					}
				}
			?>
			<div class="pmd-card pmd-card-default pmd-z-depth">
				<form action="./inbox/sendmessage" method="post">
					<div class="pmd-card-title">
						<div class="media-body media-middle">
							<h3 class="pmd-card-title-text"><div class="form-group pmd-textfield"><input type="text" class="form-control" name="messageTitle" placeholder="Enter a title here" tabindex="1" value="<?php echo (isset($_POST['messageTitle'])) ? $_POST['messageTitle'] : '';?>"></div></h3>
						</div>

						<div class="pmd-card-body">
							<table width="100%">
								<tr>
									<td width="1" valign="top"><span class="pmd-card-subtitle-text">To:</span></td>
									<td width="1">&emsp;</td>
									<td><div class="form-group pmd-textfield"><input type="text" class="form-control notification-trigger" name="messageReceiver" placeholder="" data-trigger="active" data-toggle="popover" data-placement="right" data-html="true" data-content="If you want to send to multiple people, divide the users with a ,<br /> I.e.: name1, name2, name3" tabindex="2" value="<?php echo (isset($_POST['messageReceiver'])) ? $_POST['messageReceiver'] : '';?>"></div></td>
									<td class="pull-right"><b style="font-size: 20px;"><?php echo date('g.i a'); ?></b></td>
								</tr>
								<tr>
									<td></td>
									<td>&emsp;</td>
									<td></td>
									<td class="pull-right"><span class="pmd-card-subtitle-text"><?php echo date('l, j.m.Y'); ?></span></td>
								</tr>
							</table>
							<hr>
						</div>

						<div class="pmd-card-body">
							<div class="form-group pmd-textfield">
								<label for="messageContent" class="control-label">Message</label>
								<textarea id="messageContent" name="messageContent" class="form-control" tabindex="3"><?php echo (isset($_POST['messageContent'])) ? $_POST['messageContent'] : ''; ?></textarea>
							</div>
						</div>

						<div class="pmd-card-actions">
							<button type="submit" class="btn btn-sm pmd-btn-fab pmd-btn-flat pmd-ripple-effect btn-primary pmd-tooltip" data-toggle="tooltip" data-placement="top" title="Send"><i class="material-icons pmd-sm">send</i></button>
						</div>
					</div>
				</form>
			</div>
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
