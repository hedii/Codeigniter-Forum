<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<ul class="installation-breadcrumb">
				<li><i class="fa fa-share-alt"></i> MySQL connection</li>
				<li><i class="fa fa-database"></i> Database creation</li>
				<li><i class="fa fa-table"></i> Tables creation</li>
				<li><i class="fa fa-cog"></i> Site settings</li>
				<li class="disabled"><i class="fa fa-flag-checkered"></i> Finish installation</li>
			</ul>
			<div class="page-header">
				<h1>Site settings</h1>
			</div>
			<p>Database tables creation is ok!</p>
			<p>Please provide these few site settings.</p>
			<br>
			
			<?php
				if (validation_errors()) {
					echo '<div class="alert alert-danger" role="alert">' . validation_errors() . '</div>';
				}
				if (isset($error)) {
					echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
				}
			?>
			<?php echo form_open(); ?>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="admin_username">Admin username</label>
							<input type="text" class="form-control" id="admin_username" name="admin_username" placeholder="Enter an admin username">
							<p class="help-block">This will be your admin account username on the forum</p>
						</div>
						<div class="form-group">
							<label for="admin_email">Admin email</label>
							<input type="email" class="form-control" id="admin_email" name="admin_email" placeholder="Enter your admin account email">
							<p class="help-block">This email will be used to send and receive notifications</p>
						</div>
						<div class="form-group">
							<label for="admin_password">Admin password</label>
							<input type="password" class="form-control" id="admin_password" name="admin_password" placeholder="Enter your admin password">
							<p class="help-block">This will be the password for your admin account</p>
						</div>
						<div class="form-group">
							<label for="admin_password_confirm">Confirm password</label>
							<input type="password" class="form-control" id="admin_password_confirm" name="admin_password_confirm" placeholder="Confirm your admin password">
							<p class="help-block">Must match the password you entered above</p>
						</div>
					</div>
					<div class="col-md-6">
						<hr class="visible-sm visible-xs">
						<div class="form-group">
							<label for="install_base_url">Base url</label>
							<input type="text" class="form-control" id="install_base_url" name="install_base_url" placeholder="Enter your forum base url">
							<p class="help-block">Example: http://mywebsite.com/ (don't forget the trailing slash!)</p>
						</div>
						<div class="form-group">
							<label for="install_site_title">Forum title</label>
							<input type="text" class="form-control" id="install_site_title" name="install_site_title" placeholder="Enter your forum name">
							<p class="help-block">Example: Wonderful Forums</p>
						</div>
						<div class="form-group">
							<label for="install_site_slogan">Forum slogan</label>
							<input type="text" class="form-control" id="install_site_slogan" name="install_site_slogan" placeholder="Enter your forum slogan (not required, it's up to you)">
							<p class="help-block">Example: A nice forum for a nice community</p>
	
						</div>
					</div>
				</div><!-- .row -->
				<div class="row">
					<div class="col-md-12">
						<hr>
						<input type="submit" class="btn btn-primary" value="Next">
					</div>
				</div><!-- .row -->
			</form>
		</div>
	</div><!-- .row -->
</div>