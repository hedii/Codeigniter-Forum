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
				<label for="new_topic_title">Topic title</label>
				<input type="text" class="form-control" id="new_topic_title" name="new_topic_title" placeholder="Enter a topic title">
			</div>
			<label for="new_topic_content">Topic content</label>
			
			
			<div id="wysiwygButtons">
				<button type="button" id="wysiwyg_heading" class="btn btn-default btn-sm" title="heading"><i class="fa fa-header"></i></button>
				<button type="button" id="wysiwyg_bold" class="btn btn-default btn-sm" title="bold"><i class="fa fa-bold"></i></button>
				<button type="button" id="wysiwyg_italic" class="btn btn-default btn-sm" title="italic"><i class="fa fa-italic"></i></button>
				<button type="button" id="wysiwyg_underline" class="btn btn-default btn-sm" title="underline"><i class="fa fa-underline"></i></button>
				<button type="button" id="wysiwyg_list" class="btn btn-default btn-sm" title="list"><i class="fa fa-list-ul"></i></button>
				<button type="button" id="wysiwyg_code" class="btn btn-default btn-sm" title="code"><i class="fa fa-code"></i></button>
				<button type="button" id="wysiwyg_link" class="btn btn-default btn-sm" title="link"><i class="fa fa-chain"></i></button>
				<button type="button" id="wysiwyg_unlink" class="btn btn-default btn-sm" title="unlink"><i class="fa fa-chain-broken"></i></button>
			</div>
			<textarea class="form-control" name="new_topic_content" id="new_topic_content" style="display: ;"></textarea>
			<div class="form-group">
				<div name="wysiwygEditor" id="wysiwygEditor" class="form-control" contenteditable="true"></div>
			</div>
			<!-- TODO : add email notification checkbox -->
			<input type="submit" name="wysiwygSubmit" id="wysiwygSubmit" class="btn btn-primary" value="Submit your topic">
			
			
		</form>
	</div>
</div><!-- .row -->


