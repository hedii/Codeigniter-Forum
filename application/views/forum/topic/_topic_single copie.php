<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<h1>A topic title quite long to test the table width</h1>
		</div>
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
								<small><?= $post->date ?> ago</small>
								<a href="<?= $post->permalink ?>" id="<?= $post->id ?>" style="float: right">#<?= $post->id ?></a>
								<br><br>
							</header>
							<div class="post-content">
								<p><?= $post->content ?></p>
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
	
	<div class="col-md-12 text-right">
		<?= $pagination ?>
	</div>
	
	<div class="col-md-12">
		<?php
			if (isset($error)) {
				echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
			}
			if (validation_errors()) {
				echo '<div class="alert alert-danger" role="alert">' . validation_errors() . '</div>';
			}
			if (isset($success)) {
				echo '<div class="alert alert-success" role="alert">' . $success . '</div>';
			}	
		?>
	</div>
	
	<div class="col-md-12">
		<div class="post-reply-container">
			<h3>Reply</h3>
			<?php echo form_open(); ?>
				<textarea class="form-control" name="reply_post_content" id="reply_post_content" style="display: none;"></textarea>
				
				<div id="alerts"></div>
				<div class="btn-toolbar" data-role="editor-toolbar" data-target="#editor">
				        
					<div class="btn-group">
						<a class="btn dropdown-toggle" data-toggle="dropdown" title="Font Size"><i class="icon-text-height"></i>&nbsp;<b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a data-edit="fontSize 5"><font size="6">Big</font></a></li>
							<li><a data-edit="fontSize 3"><font size="4">Normal</font></a></li>
							<li><a data-edit="fontSize 1"><font size="1">Small</font></a></li>
						</ul>
					</div>
				
					<div class="btn-group">
						<a class="btn" data-edit="bold" title="Bold (Ctrl/Cmd+B)"><i class="icon-bold"></i></a>
						<a class="btn" data-edit="italic" title="Italic (Ctrl/Cmd+I)"><i class="icon-italic"></i></a>
						<a class="btn" data-edit="strikethrough" title="Strikethrough"><i class="icon-strikethrough"></i></a>
						<a class="btn" data-edit="underline" title="Underline (Ctrl/Cmd+U)"><i class="icon-underline"></i></a>
					</div>
				
					<div class="btn-group">
						<a class="btn" data-edit="insertunorderedlist" title="Bullet list"><i class="icon-list-ul"></i></a>
						<a class="btn" data-edit="insertorderedlist" title="Number list"><i class="icon-list-ol"></i></a>
						<a class="btn" data-edit="outdent" title="Reduce indent (Shift+Tab)"><i class="icon-indent-left"></i></a>
						<a class="btn" data-edit="indent" title="Indent (Tab)"><i class="icon-indent-right"></i></a>
					</div>
				
					<div class="btn-group">
						<a class="btn dropdown-toggle" data-toggle="dropdown" title="Hyperlink"><i class="icon-link"></i></a>
						<div class="dropdown-menu input-append">
							<input class="span2" placeholder="URL" type="text" data-edit="createLink"/>
							<button class="btn" type="button">Add</button>
						</div>
						<a class="btn" data-edit="unlink" title="Remove Hyperlink"><i class="icon-cut"></i></a>
					</div>
				
					<div class="btn-group">
						<a class="btn" title="Insert picture (or just drag & drop)" id="pictureBtn"><i class="icon-picture"></i></a>
						<input type="file" data-role="magic-overlay" data-target="#pictureBtn" data-edit="insertImage">
					</div>
				
					<div class="btn-group">
						<a class="btn" data-edit="undo" title="Undo (Ctrl/Cmd+Z)"><i class="icon-undo"></i></a>
						<a class="btn" data-edit="redo" title="Redo (Ctrl/Cmd+Y)"><i class="icon-repeat"></i></a>
					</div>
				
				</div>
	
				<div id="editor"></div>
				
				<button type="submit" class="btn btn-primary">Post your reply</button>
	
			</form>
		</div><!-- .post-reply-container -->
	</div>
	
</div><!-- .row -->



