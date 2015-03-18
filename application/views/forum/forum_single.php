<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="row">
	<div class="col-md-12">
		<ol class="breadcrumb">
			<li><a href="<?= base_url('forum') ?>">Forums</a></li>
			<li class="active"><?= $forum->title ?></li>
		</ol>
	</div>
	<div class="col-md-12">
		<!--<?php var_dump($topics); ?>-->
		<div class="page-header">
			<h1><?= $forum->title ?></h1>
		</div>
		<p><?= $forum->description ?></p>
		<p>
			<?php if (!isset($_SESSION['username'])) : ?>
				<div class="alert alert-warning" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<p>Please <a href="<?= base_url('login') ?>">login</a> or <a href="<?= base_url('register') ?>">register</a> if you want to create a new topic.</p>
				</div>
			<?php else : ?>
				<a href="<?= $forum->new_topic_link ?>" class="btn btn-primary btn-sm">Create a new topic</a>
			<?php endif; ?>
		</p>
	</div>
	<div class="col-md-12">
		<?= $pagination ?>
	</div>
	<div class="col-md-12">
		<?php if (isset($topics)) : ?>
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
									<a href="<?= $topic->permalink ?>"><?= $topic->title ?></a><br>
									<small>created by <a href="<?= $topic->author->permalink ?>"><?= $topic->author->name ?></a>, <?= $topic->date ?> ago</small>
								</p>
							</td>
							<td>
								<p>
									<small><?= $topic->count_posts ?></small>
								</p>
							</td>
							<td class="hidden-xs">
								<p>
									<small>by <a href="<?= $topic->latest_post->author->permalink ?>"><?= $topic->latest_post->author->name ?></a><br><?= $topic->latest_post->date ?> ago</small></p>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php else : ?>
			<h4>No topic yet...</h4>
		<?php endif; ?>
	</div>
<!--
	<div class="col-md-12">
		<a style="float: left;" href="<?= $forum->new_topic_link ?>" class="btn btn-primary btn-sm">Create a new topic</a>
	</div>
-->

	<div class="col-md-12">
		<?= $pagination ?>
	</div>
</div><!-- .row -->