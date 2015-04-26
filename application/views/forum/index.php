<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<?= $breadcrumb ?>
		</div>
		<div class="col-md-12">
			<div class="page-header">
				<h1>Forums</h1>
			</div>
		</div>
		
		<div class="col-md-12">
			<table class="table table-striped table-condensed table-hover">
				<caption></caption>
				<thead>
					<tr>
						<th>Forums</th>
						<th>Topics</th>
						<th>Posts</th>
						<th class="hidden-xs">Latest topic</th>
					</tr>
				</thead>
				<tbody>
					<?php if ($forums) : ?>
						<?php foreach ($forums as $forum) : ?>
							<tr>
								<td>
									<p>
										<a href="<?= $forum->slug ?>"><?= $forum->title ?></a><br>
										<small><?= $forum->description ?></small>
									</p>
								</td>
								<td>
									<p>
										<small><?= $forum->count_topics ?></small>
									</p>
								</td>
								<td>
									<p>
										<small><?= $forum->count_posts ?></small>
									</p>
								</td>
								<td class="hidden-xs">
									<p>
										<small><a href="<?= $forum->latest_topic->permalink ?>"><?= $forum->latest_topic->author ?></a><br><?= $forum->latest_topic->created_at ?></small></p>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				</tbody>
			</table>
			
		</div>
		
		<?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) : ?>
			<div class="col-md-12">
				<a href="<?= base_url('create_forum') ?>" class="btn btn-default">Create a new forum</a>
			</div>
		<?php endif; ?>
		
	</div><!-- .row -->
</div><!-- .container -->

<?php //var_dump($forums); ?>