# Codeigniter-Forum
A complete forum application with automatic installer based on Codeigniter 3.x

DON'T USE IT AS IS IN A PRODUCTION SERVER, THIS FORUM APP IS STILL IN DEVELOPMENT.

I will update this repository frequently.

## Features
- Automatic installation: a built-in installation engine (database, configuration, etc...)
- Login, logout, register
- Email verification to confirm a user account (you have to edit some files to put your own email address)
- A logged in user can update his profile (username, email, new password and profile picture)
- A logged in user can delete his profile
- Administration area
- Admin can edit user rights (administrator, moderator, user)
- Forum creation for admin
- Topic creation for logged in users
- Topic post reply for logged in users
- Nice SEO friendly URLS
- No specific styles (just twitter bootstrap default styles)
- ...a lot of other functionalities coming soon...

## Installation
1. Clone this repository on your server
2. Apache user must have read/write access to files to permit automatic installation
3. Go to http://yourwebsite.tld/install
4. That's it! Just enter what you are asked by the automatic installer

The installer:

![](https://cloud.githubusercontent.com/assets/5358048/7374056/6858130a-edd0-11e4-9250-0ef62c48f584.png)

## Server Requirments
- PHP version 5.4 or newer is recommended.
- mod_rewrite enabled on your apache server.
- A database management system, MySQL recommended (I have not tested on others database systems).
