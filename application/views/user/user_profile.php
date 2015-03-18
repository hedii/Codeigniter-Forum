<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="row">
	
	<?php if (isset($_SESSION['username'])) : ?>
	
		<?php if ($_SESSION['username'] === $user->username) : ?>
	
			<div class="col-md-12">
				<div class="page-header">
					<h1>Welcome <small><?= $user->username ?></small></h1>
				</div>
			</div>
			
			<?php if (isset($admin_must_change_password)) : ?>
				<div class="col-md-12">
					<div class="alert alert-danger" role="alert"><?= $admin_must_change_password ?></div>
				</div>
			<?php endif; ?>
		
			<div class="col-md-12">
				<?php
				if (isset($error)) {
					echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
				}	
				if (validation_errors()) {
					echo '<div class="alert alert-danger" role="alert">' . validation_errors() . '</div>';
				}
				?>
				<?php if (isset($success)) : ?>
					<div class="alert alert-success alert-dismissible fade in" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<p><?= $success ?></p>
					</div>	
				<?php endif; ?>
			</div>
			
			<div class="col-md-6">
				<div class="row">
					
					<div class="col-md-12">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">User profile</h3>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-xs-3 col-sm-2 col-md-3 text-center">
										<img src="<?= $user->avatar ?>" alt="<?= $user->username ?>" class="img-circle" style="max-width: 100%; width: 100%;">
										<h4><?= $user->username ?></h4>
									</div>
									<div class="col-xs-9 col-sm-10 col-md-9">
										<p>Joined: <?= $user->registration_date ?></p>
										<?php if(isset($user->latest_post->date)) : ?>
											<p>Last active: <?= $user->latest_post->date ?></p>
										<?php else : ?>
											<p>Last active: Never...</p>
										<?php endif; ?>
										<p>Number of topics started: <?= $user->count_topics ?></p>
										<?php if ($user->count_posts > 0) : ?>
											<p>Number of posts: <a href="<?= $user->all_posts_link ?>"><?= $user->count_posts ?></a></p>
										<?php else : ?>
											<p>Number of posts: <?= $user->count_posts ?></p>
										<?php endif; ?>
									</div>
								</div><!-- .row -->
							</div>
						</div>
					</div>
					
					<div class="col-md-12">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">Manage your profile</h3>
							</div>
							<div class="panel-body">
								<?php echo form_open_multipart() ?>
									<div class="row">
										<div class="col-xs-3 col-sm-2 col-md-3">
											<br class="visible-xs">
											<img src="<?= $user->avatar ?>" alt="<?= $user->username ?>" class="img-circle" style="max-width: 100%; width: 100%;">
											<br>
										</div>
										<div class="col-xs-9 col-sm-10 col-md-9">
											<br>
											<div class="form-group">
												<label for="profile_avatar">Change your profile picture</label>
												<input type="file" id="profile_avatar" name="userfile">
											</div>
											<br><br>
										</div>
									</div><!-- .row -->
									<div class="form-group">
										<label for="profile_username">Your username</label>
										<input type="text" class="form-control" id="profile_username" name="profile_username" placeholder="<?= $user->username ?>">
									</div>
									<div class="form-group">
										<label for="profile_email">Your email</label>
										<input type="email" class="form-control" id="profile_email" name="profile_email" placeholder="<?= $user->email ?>">
									</div>
									<div class="form-group">
										<label for="profile_password">Current password</label>
										<input type="password" class="form-control" id="profile_current_password" name="profile_current_password" placeholder="Enter your password if you want to change it">
									</div>
									<div class="form-group">
										<label for="profile_password">New password</label>
										<input type="password" class="form-control" id="profile_password" name="profile_password" placeholder="Enter a new password">
									</div>
									<div class="form-group">
										<label for="profile_password_confirm">Confirm new password</label>
										<input type="password" class="form-control" id="profile_password_confirm" name="profile_password_confirm" placeholder="Confirm your new password">
									</div>
									<input type="submit" class="btn btn-primary btn-sm btn-block" value="Update your profile">
								</form>
							</div>
						</div>
					</div>
				
				</div><!-- .row -->
			</div>
			
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Recent activity</h3>
					</div>
					<div class="panel-body">
						<h5>Latest started topics</h5>
						<?php if (isset($topics)) : ?>
							<ul style="padding-left: 20px;">
								<?php foreach ($topics as $topic) : ?>
									<li><a href="<?= $topic->permalink ?>"><?= $topic->title ?></a></li>
								<?php endforeach; ?>
							</ul>
							<p><a href="<?= $user->all_topics_link ?>" class="btn btn-primary btn-sm">See all your topics</a></p>
						<?php else : ?>
							<p>You have not started any topic...</p>
						<?php endif; ?>
						<br>
						<h5>Latest posts</h5>
						<?php if (isset($posts)) : ?>
							<ul style="padding-left: 20px;">
								<?php foreach ($posts as $post) : ?>
									<li><a href="<?= $post->permalink ?>"><?= $post->topic->title ?> #<?= $post->id ?></a></li>
								<?php endforeach; ?>
							</ul>
							<p><a href="<?= $user->all_posts_link ?>" class="btn btn-primary btn-sm">See all your posts</a></p>
						<?php else : ?>
							<p>You have not posted yet...</p>
						<?php endif; ?>
					</div>
				</div><!-- .panel -->
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Delete your account</h3>
					</div>
					<div class="panel-body">
						<p>If you want to delete your account, click the button below.</p>
						<p><strong>BE CAREFUL! If you click the link below, your account will be immediately and permanently deleted. No way back!</strong></p>
						<a href="<?= $user->delete_account_link ?>" class="btn btn-danger btn-block btn-sm" onclick="return confirm('Are you sure you want to delete your account? If you click OK, your account will be immediatly and permanently deleted.')">Delete your account</a>
					</div>
				</div>
			</div>
			
		<?php else : ?>
		
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">User profile</h3>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-xs-3 col-sm-2 col-md-3 text-center">
								<img src="<?= $user->avatar ?>" alt="<?= $user->username ?>" class="img-circle" style="max-width: 100%; width: 100%;">
								<h4><?= $user->username ?></h4>
							</div>
							<div class="col-xs-9 col-sm-10 col-md-9">
								<p>Joined: <?= $user->registration_date ?></p>
								<p>Last active: <?= $user->latest_post->date ?></p>
								<p>Number of topics started: <?= $user->count_topics ?></p>
								<p>Number of posts: <?= $user->count_posts ?></p>
								<p>Contact <?= $user->username ?>: <a href="">Send a private message</a></p>
							</div>
						</div><!-- .row -->
					</div>
				</div>
			</div>
			
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Latest activity</h3>
					</div>
					<div class="panel-body">
						<p>Latest topic: <a href="<?= $user->latest_topic->permalink ?>"><?= $user->latest_topic->title ?></a></p>
						<p>Latest post: <a href="<?= $user->latest_post->permalink ?>"><?= $user->latest_post->topic_title ?> - #<?= $user->latest_post->id ?></a></p>
					</div>
				</div>
			</div>
		
		
		<?php endif; ?>
	
	<?php else : ?>
		
		<div class="col-md-12">
			<div class="alert alert-warning" role="alert">
				
				<p><strong>You have to be logged in to view a user profile.</strong></p>
				<p>Please <a href="<?= base_url('login') ?>">login</a> or <a href="<?= base_url('register') ?>">register</a>.</p>
			</div>
		</div>
		
	<?php endif; ?>
	
</div><!-- .row -->