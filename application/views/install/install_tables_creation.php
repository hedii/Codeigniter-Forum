<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<ul class="installation-breadcrumb">
				<li><i class="fa fa-share-alt"></i> MySQL connection</li>
				<li><i class="fa fa-database"></i> Database creation</li>
				<li><i class="fa fa-table"></i> Tables creation</li>
				<li class="disabled"><i class="fa fa-cog"></i> Site settings</li>
				<li class="disabled"><i class="fa fa-flag-checkered"></i> Finish installation</li>
			</ul>
			<div class="page-header">
				<h1>Tables creation</h1>
			</div>
			<p>Your database <code><?= $_COOKIE['db_name'] ?></code> has been successfully created.</p>
			<p>Please click the "Next" button below to generate the required tables for your forum.</p>
			<?php
				if (validation_errors()) {
					echo '<div class="alert alert-danger" role="alert">' . validation_errors() . '</div>';
				}
				if (isset($error)) {
					echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
				}
			?>
			<?php echo form_open(); ?>
				<input type="hidden" id="db_name_cookie" name="db_name_cookie" value="<?= $_COOKIE['db_name'] ?>">
				<input type="submit" class="btn btn-primary" value="Next">
			</form>
		</div>
	</div><!-- .row -->
</div>