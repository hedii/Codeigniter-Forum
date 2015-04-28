<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<ul class="installation-breadcrumb">
				<li><i class="fa fa-share-alt"></i> MySQL connection</li>
				<li class="disabled"><i class="fa fa-database"></i> Database creation</li>
				<li class="disabled"><i class="fa fa-table"></i> Tables creation</li>
				<li class="disabled"><i class="fa fa-cog"></i> Site settings</li>
				<li class="disabled"><i class="fa fa-flag-checkered"></i> Finish installation</li>
			</ul>
			<div class="page-header">
				<h1>MySQL connection</h1>
			</div>
			<p><strong>Welcome to Codeigniter Forums!</strong></p>
			<p>Before you can use your new forum, you have to complete this quick installation...</p>
			<p>The installation will automate the MySQL database creation process for you.</p>
			<p>First of all, please enter your MySQL <strong>hostname</strong>, <strong>username</strong> and <strong>password</strong>.</p>
			<p>Example:</p>
			<pre>hostname: localhost<br>username: root<br>password: root</pre>
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
				<div class="form-group">
					<label for="install_db_hostname">Hostname</label>
					<input type="text" class="form-control" id="install_db_hostname" name="install_db_hostname" placeholder="Enter your mysql hostname">
				</div>
				<div class="form-group">
					<label for="install_db_username">Username</label>
					<input type="text" class="form-control" id="install_db_username" name="install_db_username" placeholder="Enter your mysql username">
				</div>
				<div class="form-group">
					<label for="install_db_password">Password</label>
					<input type="text" class="form-control" id="install_db_password" name="install_db_password" placeholder="Enter your mysql password">
				</div>
				
				<input type="submit" class="btn btn-primary" value="Next">
			</form>
		</div>
	</div><!-- .row -->
</div>