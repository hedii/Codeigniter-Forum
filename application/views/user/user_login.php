<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
if (isset($error)) {
	echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
}	
if (validation_errors()) {
	echo '<div class="alert alert-danger" role="alert">' . validation_errors() . '</div>';
}
?>

<?php echo form_open('login') ?>
	<div class="form-group">
		<label for="login_username">Username</label>
		<input type="text" class="form-control" name="login_username" id="login_username" placeholder="Enter username">
	</div>
	<div class="form-group">
		<label for="login_password">Password</label>
		<input type="password" class="form-control" name="login_password" id="login_password" placeholder="Enter password">
	</div>
	<button type="submit" class="btn btn-default">Login</button>
</form>