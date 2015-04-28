<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<?= $breadcrumb ?>
		</div>
		<div class="col-md-12">
			<div class="page-header">
				<h1>User account deletion <small><?= $user->username ?></small></h1>
			</div>
		</div>
		<?php if (isset($success)) : ?>
			<div class="col-md-12">
				<div class="alert alert-success" role="alert">
					<p><?= $success ?></p>
					<p>Go back to <a href="<?= base_url() ?>">Home page</a></p>
				</div>
			</div>
		<?php endif; ?>
	</div><!-- .row -->
</div><!-- .container -->

<?php session_destroy() ?>