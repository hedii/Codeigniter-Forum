<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<?= $breadcrumb ?>
		</div>
		<div class="col-md-12">
			<div class="page-header">
				<h1><?= $forum->title ?></h1>
				<p><?= $forum->description ?></p>
			</div>
		</div>
		
		<div class="col-md-12">
			<?php if (isset($topics) && !empty($topics)) : ?>
				<table class="table table-striped table-condensed table-hover">
					<caption></caption>
					<thead>
						<tr>
							<th>Topics</th>
							<th>Posts</th>
							<th class="hidden-xs">Latest posts</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($topics as $topic) : ?>
							<tr>
								<td>
									<p>
										<a href="<?= base_url($topic->permalink) ?>"><?= $topic->title ?></a><br>
										<small>created by <a href="<?= base_url('user/' . $topic->author) ?>"><?= $topic->author ?></a>, <?= $topic->created_at ?></small>
									</p>
								</td>
								<td>
									<p>
										<small><?= $topic->count_posts ?></small>
									</p>
								</td>
								<td class="hidden-xs">
									<p>
										<small>by <a href="<?= base_url('user/' . $topic->latest_post->author) ?>"><?= $topic->latest_post->author ?></a><br><?= $topic->latest_post->created_at ?></small></p>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php else : ?>
				<h4>No topic yet...</h4>
			<?php endif; ?>
		</div>
		
		<?php if (isset($_SESSION['user_id'])) : ?>
			<div class="col-md-12">
				<a href="<?= base_url($forum->slug . '/create_topic') ?>" class="btn btn-default">Create a new topic</a>
			</div>
		<?php endif; ?>
		
	</div><!-- .row -->
</div><!-- .container -->

<?php //var_dump($forum, $topics); ?>