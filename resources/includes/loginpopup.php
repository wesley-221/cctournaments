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
						<b>Have an account already?</b>
					</div>
				</div>

				<form action="./" method="post">
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group pmd-textfield pmd-textfield-floating-label">
								<label for="loginUsername" class="control-label pmd-input-group-label">Username</label>
								<div class="input-group">
									<div class="input-group-addon"><i class="material-icons pmd-sm">perm_identity</i></div>
									<input id="loginUsername" name="loginUsername" type="text" class="form-control">
								</div>
							</div>
						</div>
					</div>

					<div class="extraSpacing3"></div>

					<div class="row">
						<div class="col-sm-12">
							<div class="form-group pmd-textfield pmd-textfield-floating-label">
								<label for="loginPassword" class="control-label pmd-input-group-label">Password</label>
								<div class="input-group">
									<div class="input-group-addon"><i class="material-icons pmd-sm">lock_outline</i></div>
									<input id="loginPassword" name="loginPassword" type="password" class="form-control">
								</div>
							</div>
						</div>
					</div>

					<div class="extraSpacing10"></div>

					<div class="row">
						<div class="col-sm-12">
							<button type="submit" class="btn btn-primary pmd-btn-raised form-control">Log in</button>
						</div>
					</div>
				</form>

				<hr />

				<div class="row">
					<div class="col-sm-12">
						<a href="./register" class="btn btn-default pmd-btn-raised form-control">Create an account</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
