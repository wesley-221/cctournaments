<nav class="navbar navbar-default navbar-fixed">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbarId" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="./"><?php echo $serverSettings['brandName']; ?></a>
		</div>

		<div class="collapse navbar-collapse" id="navbarId">
			<ul class="nav navbar-nav navbar-left">
				<li><a href="./about">About</a></li>
				<li><a href="./tournaments">Tournaments</a></li>
				<li><a href="./teams">Teams</a></li>
				<li><a href="./rankings">Rankings</a></li>
			</ul>

			<ul class="nav navbar-nav navbar-right">
				<?php
					if($userData["loggedin"] == 1) {
						if($userData["permissionId"] == 3) {
							echo '<li><a href="./admin">Adminpanel</a></li>';
						}

						echo '<li><a href="./report">Report</a></li>';
						echo '<li><a href="./todo">Todo list</a></li>';

						$emailsUnread = $db -> fetch('SELECT count(messageId) AS count FROM messages WHERE messageReceiverId = ? AND messageRead = 0', [$userData['userId']]);

						echo '<li><a href="./inbox">Inbox <span class="badge">' . $emailsUnread['count'] . '</span></a></li>';
						echo '<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog" style="font-size: 16px;"></i></span></a>
							<ul class="dropdown-menu">
								<li><a href="./profile/' . $userData['userId'] . '">My profile</a></li>
								<li><a href="./settings">Settings</a></li>
								<li role="separator" class="divider"></li>
								<li><a href="./logout">Log out</a></li>
							</ul>
						</li>';
					}
					else {
						echo '<li><a href="#" data-toggle="modal" data-target="#loginModal">Have an account? Log in.</a></li>';
					}
				?>
			</ul>
		</div>
	</div>
</nav>
