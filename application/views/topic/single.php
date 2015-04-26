<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="page-header">
				<h1><?= $topic->title ?></h1>
			</div>
		</div>
		
		<?php foreach ($posts as $post) : ?>
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title"><?= $post->author ?>, <?= $post->created_at ?></h3>
					</div>
					<div class="panel-body">
						<?= $post->content ?>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
		
		<?php if (isset($_SESSION['user_id'])) : ?>
			<div class="col-md-12">
				<a href="<?= base_url($forum->slug . '/' . $topic->slug . '/reply') ?>" class="btn btn-default">Reply to the topic</a>
			</div>
		<?php endif; ?>
		
	</div><!-- .row -->
</div><!-- .container -->

<?php var_dump($forum, $topic, $posts); ?>