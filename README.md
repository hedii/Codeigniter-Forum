# Codeigniter-Forum
A complete forum application based on Codeigniter 3.x

DON'T USE IT AS IT IS IN A PRODUCTION SERVER, THIS FORUM APP IS STILL IN DEVELOPMENT.

I will update this repository frequently.

## Features
- login, logout, register
- email verification to confirm a user account
- a logged in user can update his profile (username, email, new password and profile picture)
- administration area
- admin can edit user rights (administrator, moderator, user)
- forum creation for admin
- topics creation for logged in users
- topics post reply for logged in users
- nice SEO friendly URLS
- No specific styles (just twitter bootstrap default styles)
- ...a lot of other functionalities coming soon...

## Installation
1. Clone this repository on your server
2. Create a database
3. Edit /application/config/database.php with your database connection informations
4. Copy, paste and execute this SQL command to create SQL tables:

```sql
CREATE TABLE IF NOT EXISTS `ci_sessions` (
	`id` varchar(40) NOT NULL,
	`ip_address` varchar(45) NOT NULL,
	`timestamp` int(10) unsigned DEFAULT 0 NOT NULL,
	`data` blob NOT NULL,
	PRIMARY KEY (id),
	KEY `ci_sessions_timestamp` (`timestamp`)
);

CREATE TABLE IF NOT EXISTS `users` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`username` varchar(255) NOT NULL DEFAULT '',
	`email` varchar(255) NOT NULL DEFAULT '',
	`password` varchar(255) NOT NULL DEFAULT '',
	`avatar` varchar(255) DEFAULT 'default.jpg',
	`created_at` datetime NOT NULL,
	`updated_at` datetime DEFAULT NULL,
	`updated_by` int(11) unsigned NOT NULL,
	`is_admin` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`is_moderator` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`is_confirmed` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`is_deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `forums` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`title` varchar(255) NOT NULL DEFAULT '',
	`slug` varchar(255) NOT NULL DEFAULT '',
	`description` varchar(255) DEFAULT NULL,
	`created_at` datetime NOT NULL,
	`updated_at` datetime DEFAULT NULL,
	PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `topics` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`title` varchar(255) NOT NULL DEFAULT '',
	`slug` varchar(255) NOT NULL DEFAULT '',
	`created_at` datetime NOT NULL,
	`updated_at` datetime DEFAULT NULL,
	`user_id` int(11) unsigned NOT NULL,
	`forum_id` int(11) unsigned NOT NULL,
	`is_sticky` tinyint(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `posts` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`content` longtext NOT NULL,
	`user_id` int(11) unsigned NOT NULL,
	`topic_id` int(11) unsigned NOT NULL,
	`created_at` datetime NOT NULL,
	`updated_at` datetime DEFAULT NULL,
	PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `options` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(255) NOT NULL DEFAULT '',
	`value` varchar(255) NOT NULL DEFAULT '',
	PRIMARY KEY (`id`)
);
```

Go to http://example.com/register create a user, and assign him manualy admin rights on the users table (this will be handled automaticaly soon).
