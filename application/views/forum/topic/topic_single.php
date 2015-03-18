<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="row">
	<div class="col-md-12">
		<ol class="breadcrumb">
			<li><a href="<?= base_url('forum') ?>">Forums</a></li>
			<li><a href="<?= base_url('forum/' . $forum->slug) ?>"><?= $forum->title ?></a></li>
			<li class="active"><?= $topic->title ?></li>
		</ol>
	</div>
	<div class="col-md-12">
		<div class="page-header">
			<h1><?= $topic->title ?></h1>
		</div>
	</div>
	
	<?php if (!isset($_SESSION['username'])) : ?>
		<div class="col-md-12">
			<div class="alert alert-warning" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<p>Please <a href="<?= base_url('login') ?>">login</a> or <a href="<?= base_url('register') ?>">register</a> if you want to reply to this topic.</p>
			</div>
		</div>
	<?php endif; ?>
	
	<div class="top-pagination col-md-12">
		<?= $pagination ?>
	</div>

	<?php foreach ($posts as $post) : ?>	
		<article class="col-md-12 post">
			<div class="row">
				<div class="col-xs-2 col-md-1 text-center">
					<p>
						<img src="<?= $post->author->avatar ?>" alt="<?= $post->author->name ?>" class="img-circle" style="max-width: 100%; width: 100%;">
						<small><a href="<?= $post->author->permalink ?>"><?= $post->author->name ?></a><br><?= $post->author->count_topics ?> topics<br><?= $post->author->count_posts ?> posts</small>
					</p>
				</div>
				<div class="col-xs-10 col-md-11">
					<div class="panel panel-default">
						<div class="panel-body">
							<header class="post-header">
								<small><?= $post->date ?></small>
								<small><a href="<?= $post->permalink ?>" id="<?= $post->id ?>" style="float: right">#<?= $post->id ?></a></small>
							</header>
							<div class="post-content">
								<?= $post->content ?>
							</div><!-- .post-content -->
							<footer class="post-footer">
								<a href=""><small>Report</small></a>
								&middot;
								<a href=""><small>Edit</small></a>
							</footer>
						</div><!-- .panel-body -->
					</div><!-- .panel -->
				</div>
			</div><!-- .row -->
		</article><!-- .post -->
	<?php endforeach; ?>
	
	<div class="bottom-pagination col-md-12">
		<?= $pagination ?>
	</div>
	<?php if (!isset($_SESSION['username'])) : ?>
		<div class="col-md-12">
			<div class="alert alert-warning" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<p>Please <a href="<?= base_url('login') ?>">login</a> or <a href="<?= base_url('register') ?>">register</a> if you want to reply to this topic.</p>
			</div>
		</div>
	<?php else : ?>
		<?php
			if (isset($error)) {
				echo '<div class="col-md-12"><div class="alert alert-danger" role="alert">' . $error . '</div></div>';
			}
			if (validation_errors()) {
				echo '<div class="col-md-12"><div class="alert alert-danger" role="alert">' . validation_errors() . '</div></div>';
			}
			if (isset($success)) {
				echo '<div class="col-md-12"><div class="alert alert-success" role="alert">' . $success . '</div></div>';
			}
		?>
		<div class="col-md-12">
			<div class="post-reply-container">
				<h3>Reply</h3>
				<?php echo form_open(); ?>
					<div id="wysiwygButtons">
						<button type="button" id="wysiwyg_heading" class="btn btn-default btn-sm" title="heading"><i class="fa fa-header"></i></button>
						<button type="button" id="wysiwyg_bold" class="btn btn-default btn-sm" title="bold"><i class="fa fa-bold"></i></button>
						<button type="button" id="wysiwyg_italic" class="btn btn-default btn-sm" title="italic"><i class="fa fa-italic"></i></button>
						<button type="button" id="wysiwyg_underline" class="btn btn-default btn-sm" title="underline"><i class="fa fa-underline"></i></button>
						<button type="button" id="wysiwyg_list" class="btn btn-default btn-sm" title="list"><i class="fa fa-list-ul"></i></button>
						<button type="button" id="wysiwyg_code" class="btn btn-default btn-sm" title="code"><i class="fa fa-code"></i></button>
						<button type="button" id="wysiwyg_image" class="btn btn-default btn-sm" title="image"><i class="fa fa-file-image-o"></i></button>
						<button type="button" id="wysiwyg_link" class="btn btn-default btn-sm" title="link"><i class="fa fa-chain"></i></button>
						<button type="button" id="wysiwyg_unlink" class="btn btn-default btn-sm" title="unlink"><i class="fa fa-chain-broken"></i></button>
					</div>
					<textarea class="form-control" name="reply_post_content" id="reply_post_content" style="display: none;"></textarea>
					<div class="form-group">
						<div name="wysiwygEditor" id="wysiwygEditor" class="form-control" contenteditable="true"></div>
					</div>
					<!-- TODO : add email notification checkbox -->
					<input type="submit" name="wysiwygSubmit" id="wysiwygSubmit" class="btn btn-primary" value="Submit your post">
				</form>
			</div><!-- .post-reply-container -->
		</div>
	<?php endif; ?>
	
</div><!-- .row -->



