<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
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
			
			<input type="submit" class="btn btn-primary" value="Next">
		</form>
	</div>
</div><!-- .row -->