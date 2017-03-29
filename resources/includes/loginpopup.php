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

				<form action="./" method="post">
					<div class="row">
						<div class="col-sm-2">
							<label for="loginUsername">Username</label>
						</div>

						<div class="col-sm-10">
							<input id="loginUsername" name="loginUsername" type="text" class="form-control" />
						</div>
					</div>

					<div class="extraSpacing3"></div>

					<div class="row">
						<div class="col-sm-2">
							<label for="loginUsername">Password</label>
						</div>

						<div class="col-sm-10">
							<input id="loginPassword" name="loginPassword" type="password" class="form-control" />
						</div>
					</div>

					<div class="extraSpacing10"></div>

					<div class="row">
						<div class="col-sm-12">
							<button type="submit" class="btn btn-primary form-control">Log in</button>
						</div>
					</div>
				</form>

				<hr />

				<div class="row">
					<div class="col-sm-12">
						<a href="./register.php" class="btn btn-default form-control">Create an account</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
