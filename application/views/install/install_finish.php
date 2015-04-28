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
				<li><i class="fa fa-flag-checkered"></i> Finish installation</li>
			</ul>
			<div class="page-header">
				<h1>Finish the installation</h1>
			</div>
			<p>Forum settings are ok!</p>
			<p>The installation process is finished.<br>For security reasons, all files related to this installer will be deleted after you click the "Finish the installation" button.</p>
			<p>You the will be redirected to the home page of your forum.</p>
			<p>To login as admin:</p>
			<pre>username: admin<br>password: admin<br><strong>(!!!don't forget to change these identifiers after you're logged in!!!)</strong></pre>
			<p><strong>Have fun with Codeigniter Forums :)</strong></p>
			<br>
			
			<a class="btn btn-primary" href="<?= base_url('install/delete_files') ?>">Finish the installation</a>
		</div>
	</div><!-- .row -->
</div>