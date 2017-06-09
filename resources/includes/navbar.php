<nav class="navbar navbar-default navbar-fixed">
	<?php
		$aboutActive 		= (!strcmp($curPage, 'about')) ? 'active' : '';
		$tournamentsActive 	= (!strcmp($curPage, 'tournaments')) ? 'active' : '';
		$teamsActive 		= (!strcmp($curPage, 'teams')) ? 'active' : '';
		$rankingsActive 	= (!strcmp($curPage, 'rankings')) ? 'active' : '';
		$forumActive 		= (!strcmp($curPage, 'forum')) ? 'active' : '';
		$adminActive		= (!strcmp($curPage, 'admin')) ? 'active' : '';
		$reportActive 		= (!strcmp($curPage, 'report')) ? 'active' : '';
		$todoActive			= (!strcmp($curPage, 'todo')) ? 'active' : '';
		$inboxActive		= (!strcmp($curPage, 'inbox')) ? 'active' : '';
		$profileActive		= (!strcmp($curPage, 'profile')) ? 'active' : '';
		$settingsActive		= (!strcmp($curPage, 'settings')) ? 'active' : '';
	?>
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
				<li class="<?php echo $aboutActive; ?>"><a href="./about">About</a></li>
				<li class="<?php echo $tournamentsActive; ?>"><a href="./tournaments">Tournaments</a></li>
				<li class="<?php echo $teamsActive; ?>"><a href="./teams">Teams</a></li>
				<li class="<?php echo $rankingsActive; ?>"><a href="./rankings">Rankings</a></li>
				<li class="<?php echo $forumActive; ?>"><a href="./forum">Forum</a></li>
			</ul>

			<ul class="nav navbar-nav navbar-right">
				<?php
					if($userData["loggedin"] == 1) {
						if($userData["permissionId"] == 3) {
							echo '<li class="' . $adminActive . '"><a href="./admin">Adminpanel</a></li>';
						}

						echo '<li class="' . $reportActive . '"><a href="./report">Report</a></li>';
						echo '<li class="' . $todoActive . '"><a href="./todo">Todo list</a></li>';

						$emailsUnread = $db -> fetch('SELECT count(messageId) AS count FROM messages WHERE messageReceiverId = ? AND messageRead = 0', [$userData['userId']]);

						echo '<li class="' . $inboxActive . '"><a href="./inbox">Inbox <span class="badge">' . $emailsUnread['count'] . '</span></a></li>';
						echo '<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog" style="font-size: 16px;"></i></span></a>
							<ul class="dropdown-menu">
								<li class="' . $profileActive . '"><a href="./profile/' . $userData['userId'] . '">My profile</a></li>
								<li class="' . $settingsActive . '"><a href="./settings">Settings</a></li>
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
