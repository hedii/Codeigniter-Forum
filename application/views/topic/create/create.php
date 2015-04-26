<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container">
	<div class="row">
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
			<div class="page-header">
				<h1>Create a new topic</h1>
			</div>
			<?= form_open() ?>
				<div class="form-group">
					<label for="title">Topic title</label>
					<input type="text" class="form-control" id="title" name="title" placeholder="Enter a topic title">
				</div>
				<div class="form-group">
					<label for="content">Content</label>
					<textarea rows="6" class="form-control" id="content" name="content" placeholder="Enter your topic content here"></textarea>
				</div>
				<div class="form-group">
					<input type="submit" class="btn btn-default" value="Create topic">
				</div>
			</form>
		</div>
	</div><!-- .row -->
</div><!-- .container -->