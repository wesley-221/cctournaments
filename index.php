<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>CustomAllOsu</title>

		<meta name="viewport" content="minimum-scale=1.0, width=device-width, maximum-scale=1.0, user-scalable=no" />
		<link href="css/bootstrap.min.css" rel="stylesheet" />
		<link href="resources/fontawesome/css/font-awesome.min.css" rel="stylesheet" />
		<link href="css/style.css" rel="stylesheet" />
	</head>

	<body>
		<div class="banner">
			<div class="bannerContent pull-right">
				<a href="https://twitter.com/TheCCstaff"><i class="fa fa-twitter fa-5x" style="color: #0084b4;" aria-hidden="true"></i></a>
				<a href="https://www.twitch.tv/cctournaments"><i class="fa fa-twitch fa-5x" style="color: #6441a5;" aria-hidden="true"></i></a>
				<a href="https://www.youtube.com/channel/UCtJoblXanG9fRMMQrcVEb2w"><i class="fa fa-youtube fa-5x" style="color: #bb0000;" aria-hidden="true"></i></a>
			</div>
		</div>

		<nav class="navbar navbar-default">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="#">CCTournaments</a>
				</div>

				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav navbar-left">
						<li><a href="#">About</a></li>
						<li><a href="#">Ongoing tournaments</a></li>
						<li><a href="#">Rankings</a></li>
					</ul>

					<ul class="nav navbar-nav navbar-right">
						<li><a href="#" data-toggle="modal" data-target="#loginModal">Have an account? Log in.</a></li>
					</ul>
				</div>
			</div>
		</nav>

		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-offset-1 col-lg-7 col-sm-6">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title">News</h3>
						</div>

						<div class="panel-body">
							Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
						</div>
					</div>
				</div>

				<div class="col-lg-4 col-sm-6">
					<div class="row">
						<div class="col-sm-12">
							<div class="panel panel-primary twitter">
								<div class="panel-heading">
									<h3 class="panel-title">Twitter</h3>
								</div>

								<div class="panel-body">

								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-sm-12">
							<div class="panel panel-primary twitch">
								<div class="panel-heading">
									<h3 class="panel-title">Twitch</h3>
								</div>

								<div class="panel-body">

								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-sm-12">
							<div class="panel panel-primary youtube">
								<div class="panel-heading">
									<h3 class="panel-title">Youtube</h3>
								</div>

								<div class="panel-body">

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="loginModalLabel">Login</h4>
					</div>

					<div class="modal-body">
						<div class="row">
							<div class="col-sm-12">
								<p>Have an account?</p>
							</div>
						</div>

						<div class="row">
							<div class="col-sm-2">
								<label for="loginUsername">Username</label>
							</div>

							<div class="col-sm-10">
								<input id="loginUsername" type="text" class="form-control" />
							</div>
						</div>

						<div class="extraSpacing3"></div>

						<div class="row">
							<div class="col-sm-2">
								<label for="loginUsername">Password</label>
							</div>

							<div class="col-sm-10">
								<input id="loginPassword" type="password" class="form-control" />
							</div>
						</div>

						<div class="extraSpacing10"></div>

						<div class="row">
							<div class="col-sm-12">
								<a href="#" class="btn btn-primary form-control">Log in</a>
							</div>
						</div>

						<hr />

						<div class="row">
							<div class="col-sm-12">
								<a href="#" class="btn btn-default form-control">Create an account</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>

	<script src="js/jquery-3.2.0.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</html>
