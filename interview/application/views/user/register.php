
<div class="container">
	<div class="row">
		<?php if (validation_errors()) : ?>
			<div class="col-md-12">
				<div class="alert alert-danger" role="alert">
					<?= validation_errors() ?>
				</div>
			</div>
		<?php endif; ?>
		<?php if (isset($error)) : ?>
			<div class="col-md-12">
				<div class="alert alert-danger" role="alert">
					<?= $error ?>
				</div>
			</div>
		<?php endif; ?>
		<div class="col-md-4 col-md-offset-4 well">
			<div class="page-header">
				<h1>Register</h1>
			</div>
			<?= form_open() ?>
                        <div class="form-group">
                                <label for="fname">First Name</label>
                                <input type="text" class="form-control" id="fname" name="fname" placeholder="Enter your first name">					
                        </div>
                    
                    <div class="form-group">
                        <label for="lname">Last Name</label>
                        <input type="text" class="form-control" id="lname" name="lname" placeholder="Enter your lastname">					
                    </div>
                    
                    
                    <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email">					
                    </div>
                    <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter a password">
                    </div>
                    <div class="form-group">
                            <label for="password_confirm">Confirm password</label>
                            <input type="password" class="form-control" id="password_confirm" name="password_confirm" placeholder="Confirm your password">
                    </div>
                    <div class="form-group">
                            <input type="submit" class="btn btn-default" value="Register">
                    </div>
			
                </form>
		</div>
	</div><!-- .row -->
</div>