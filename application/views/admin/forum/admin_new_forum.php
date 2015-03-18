<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="row">
	<?php // Form validation
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
		<?php echo form_open(); ?>
			<div class="form-group">
				<label for="new_topic_title">Forum title</label>
				<input type="text" class="form-control" id="new_forum_title" name="new_forum_title" placeholder="Enter a forum title">
			</div>
			<div class="form-group">
				<label for="new_forum_description">Forum description</label>
				<input type="" class="form-control" id="new_forum_description" name="new_forum_description" placeholder="Enter a forum description">
			</div>
			<input type="submit" name="new_forum_submit" id="new_forum_submit" class="btn btn-primary" value="Submit the new forum">
			
		</form>
	</div>
</div><!-- .row -->