<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
if (isset($error)) {
	echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
}	
	
	
if (validation_errors()) {
	echo '<div class="alert alert-danger" role="alert">' . validation_errors() . '</div>';
}
?>

<?php echo form_open('register') ?>
	<div class="form-group">
		<label for="register_username">Username</label>
		<input type="text" class="form-control" name="register_username" id="register_username" placeholder="Enter username" value="<?= set_value('register_username'); ?>">
	</div>
	<div class="form-group">
		<label for="register_password">Password</label>
		<input type="password" class="form-control" name="register_password" id="register_password" placeholder="Enter password">
	</div>
	<div class="form-group">
		<label for="register_password_confirmation">Password confirmation</label>
		<input type="password" class="form-control" name="register_password_confirmation" id="register_password_confirmation" placeholder="Confirm password">
	</div>
	<div class="form-group">
		<label for="register_email">Email</label>
		<input type="email" class="form-control" name="register_email" id="register_email" placeholder="Enter email" value="<?= set_value('register_email'); ?>">
	</div>
	<button type="submit" class="btn btn-default">Register</button>
</form>