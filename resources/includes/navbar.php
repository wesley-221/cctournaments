<nav class="navbar navbar-default">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="./"><?php echo $serverSettings['brandName']; ?></a>
		</div>

		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav navbar-left">
				<li><a href="about.php">About</a></li>
				<li><a href="#">Ongoing tournaments</a></li>
				<li><a href="#">Rankings</a></li>
			</ul>

			<ul class="nav navbar-nav navbar-right">
				<?php
					if($userData["loggedin"] == 1) {
						echo '<li><a href="#">My profile</a></li>';

						if($userData["permissionId"] == 3) {
							echo '<li><a href="./serversettings.php">Server Settings</a></li>';
						}

						echo '<li><a href="./settings.php"><i class="fa fa-cog" style="font-size: 16px;"></i></a></li>';
					}
					else {
						echo '<li><a href="#" data-toggle="modal" data-target="#loginModal">Have an account? Log in.</a></li>';
					}
				?>
			</ul>
		</div>
	</div>
</nav>
