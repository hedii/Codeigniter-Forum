<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="page-header">
				<h1>User account email confirmation</h1>
			</div>
			<?php if (isset($error)) : ?>
				<div class="col-md-12">
					<div class="alert alert-danger" role="alert">
						<p><?= $error ?></p>
					</div>
				</div>
			<?php endif; ?>
			<?php if (isset($success)) : ?>
				<div class="col-md-12">
					<div class="alert alert-success" role="alert">
						<p><?= $success ?></p>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div><!-- .row -->
</div><!-- .container -->