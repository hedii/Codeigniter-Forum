<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<?= $breadcrumb ?>
		</div>
		<div class="col-md-12">
			<div class="page-header">
				<h1>Create a new forum</h1>
			</div>
		</div>
		<?php if ($login_as_admin_needed) : ?>
			<div class="col-md-12">
				<div class="alert alert-danger" role="alert">
					<p>You need to be logged in as an administrator to create a new forum!</p>
					<p>Please <a href="<?= base_url('login') ?>">login</a>.</p>
				</div>
			</div>
		<?php else : ?>
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
						<label for="title">Title</label>
						<input type="text" class="form-control" id="title" name="title" placeholder="Enter a forum title" value="<?= $title ?>">
					</div>
					<div class="form-group">
						<label for="description">Description</label>
						<textarea rows="6" class="form-control" id="description" name="description" placeholder="Enter short description for the new forum (max 80 characters)"><?= $description ?></textarea>
					</div>
					<div class="form-group">
						<input type="submit" class="btn btn-default" value="Create forum">
					</div>
				</form>
			</div>
		<?php endif; ?>
	</div><!-- .row -->
</div><!-- .container -->