<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>forum2.dev | header.php</title>
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="author" content="">

	<!-- css -->
	<link href="<?= base_url() ?>css/bootstrap.min.css" rel="stylesheet">
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
	<link href="<?= base_url() ?>js/google-code-prettify/prettify.css" rel="stylesheet">
	<link href="<?= base_url() ?>css/style.css" rel="stylesheet">

	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>

	<header id="site-header">
		<nav class="navbar navbar-default" role="navigation">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="<?= base_url() ?>">Home</a>
				</div>
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav navbar-right">
						<li><a href="<?= base_url(); ?>forum">Forum</a></li>
						<?php if (isset($_SESSION['username']) && $_SESSION['logged_in'] === true) : ?>
							<li><a href="<?= base_url(); ?>logout">Logout</a></li>
							<li><a href="<?= base_url('user/' . $_SESSION['username']); ?>">Your profile</a></li>
							<?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) : ?>
								<li><a href="<?= base_url('admin'); ?>">Administration</a></li>
							<?php endif; ?>
						<?php else : ?>
							<li><a href="<?= base_url(); ?>login">Login</a></li>
							<li><a href="<?= base_url(); ?>register">Register</a></li>
						<?php endif; ?>
					</ul>
				</div><!-- .navbar-collapse -->
			</div><!-- .container-fluid -->
		</nav><!-- .navbar -->
	</header><!-- #site-header -->

	<main id="site-content" class="container" role="main">
		
		<div class="row">
			<div class="col-md-12">
				DEBUG SESSION : <br>
				<?php var_dump($_SESSION); ?>
			</div>
		</div>


