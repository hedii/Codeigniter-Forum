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
		
		<?php if (validation_errors()) : ?>
			<div class="col-md-12">
				<div class="alert alert-danger" role="alert">
					<?= validation_errors() ?>
				</div>
			</div>
		<?php endif; ?>
		<?php if (isset($error)) : ?>
			<div class="col-md-12">
				<div class="alert alert-danger" role="alert">
					<?= $error ?>
				</div>
			</div>
		<?php endif; ?>
		<div class="col-md-12">
			<?= form_open() ?>
				<div class="form-group">
					<label for="reply">Reply</label>
					<textarea rows="6" class="form-control" id="reply" name="reply" placeholder=""><?= $content ?></textarea>
				</div>
				<div class="form-group">
					<input type="submit" class="btn btn-default" value="Reply">
				</div>
			</form>
		</div>
		
	</div><!-- .row -->
</div><!-- .container -->

<?php //var_dump($forum, $topic, $posts); ?>