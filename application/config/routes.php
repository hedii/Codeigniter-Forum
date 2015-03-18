<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
$route['default_controller'] = 'forum';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

// Forum controller
$route['forum/(:any)'] = 'forum/index/$1';
$route['forum/(:any)/(:num)'] = 'forum/index/$1'; // forum pagination
$route['forum/(:any)/new'] = 'forum/new_topic'; // new topic
$route['forum/(:any)/(:any)'] = 'forum/topic/$2';
$route['forum/(:any)/(:any)/(:num)'] = 'forum/topic/$3'; // topic pagination

// User Controller
$route['login'] = 'user/login';
$route['logout'] = 'user/logout';
$route['email_validation'] = 'user/email_validation';
$route['register'] = 'user/register';
$route['user/(:any)'] = 'user/index/$1';

// Admin Controller
$route['admin/forum/new'] = 'admin/new_forum';

$route['default_controller'] = 'install';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;