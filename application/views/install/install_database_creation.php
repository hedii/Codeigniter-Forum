<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<ul class="installation-breadcrumb">
				<li><i class="fa fa-share-alt"></i> MySQL connection</li>
				<li><i class="fa fa-database"></i> Database creation</li>
				<li class="disabled"><i class="fa fa-table"></i> Tables creation</li>
				<li class="disabled"><i class="fa fa-cog"></i> Site settings</li>
				<li class="disabled"><i class="fa fa-flag-checkered"></i> Finish installation</li>
			</ul>
			<div class="page-header">
				<h1>Database creation</h1>
			</div>
			<p>Connexion to MySQL is ok!</p>
			<p>Please Enter a name for the database that will host your forum datas.<br>The database will be created <strong>automatically</strong> for you.</p>
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
					<label for="database_name">Database name</label>
					<input type="text" class="form-control" id="database_name" name="database_name" placeholder="Enter a database name">
				</div>
	
				<input type="submit" class="btn btn-primary" value="Next">
			</form>
		</div>
	</div><!-- .row -->
</div>