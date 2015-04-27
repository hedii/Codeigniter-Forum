<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container">
	<div class="row">
		<div class="col-md-2">
			<ul class="nav nav-pills nav-stacked">
				<li role="presentation" class="active"><a href="#">Home</a></li>
				<li role="presentation"><a href="<?= base_url('admin/users') ?>">Users</a></li>
				<li role="presentation"><a href="<?= base_url('admin/forum_and_topics') ?>">Forums & topics</a></li>
				<li role="presentation"><a href="<?= base_url('admin/options') ?>">Options</a></li>
				<li role="presentation"><a href="<?= base_url('admin/emails') ?>">Emails</a></li>
			</ul>
		</div>
		<div class="col-md-10">
			
		</div>
	</div><!-- .row -->
</div><!-- .container -->