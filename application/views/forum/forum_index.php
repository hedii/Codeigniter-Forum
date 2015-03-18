<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="row">
	<div class="col-md-12">
		<ol class="breadcrumb">
			<li class="active">Forums</li>
		</ol>
	</div>
<!--
	<div class="col-md-12">
		<div class="alert alert-info alert-dismissible fade in">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
			There are <?= $count_all_forums ?> forums containing a total of <?= $count_all_topics ?> topics.<br><small>Last topic 3 minutes ago <a href=""><strong>by hedi</strong></a></small>
		</div>
	</div>
-->
	<div class="col-md-12">
		<div class="page-header">
			<h1>Site title <small>and a site slogan</small></h1>
		</div>
		<p>
			Welcome to Site title forums.<br>
			<?php if (!isset($_SESSION['username'])) : ?>
				<span>Please <a href="<?= base_url('login') ?>">login</a> or <a href="<?= base_url('register') ?>">register</a> if you want to create a new topic or reply to an existant one.<br></span>
			<?php endif; ?>
		</p>
	</div>
	<div class="col-md-12">
		<!--<?php var_dump($forums); ?>-->
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
									<a href="<?= $forum->permalink ?>"><?= $forum->title ?></a><br>
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
									<small><a href="<?= $forum->latest_topic->permalink ?>"><?= $forum->latest_topic->author_name ?></a><br><?= $forum->latest_topic->date ?></small></p>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
		
	</div>
</div><!-- .row -->
