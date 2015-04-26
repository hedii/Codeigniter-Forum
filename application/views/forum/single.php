<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="page-header">
				<h1><?= $forum->title ?></h1>
				<p><?= $forum->description ?></p>
			</div>
		</div>
		
		<?php if (isset($_SESSION['user_id'])) : ?>
			<div class="col-md-12">
				<a href="<?= base_url($forum->slug . '/create_topic') ?>" class="btn btn-default">Create a new topic</a>
			</div>
		<?php endif; ?>
		
	</div><!-- .row -->
</div><!-- .container -->

<?php var_dump($forum); ?>