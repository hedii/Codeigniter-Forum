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
/**
 * Install routes
 */
$route['install'] = 'install/index';
$route['install/database_creation'] = 'install/database_creation';
$route['install/tables_creation'] = 'install/tables_creation';
$route['install/site_settings'] = 'install/site_settings';
$route['install/finish'] = 'install/finish';
$route['install/delete_files'] = 'install/delete_files';

/**
 * Admin routes
 */
$route['admin'] = 'admin/index';
$route['admin/users'] = 'admin/users';
$route['admin/edit_user'] = 'admin/edit_user';
$route['admin/edit_user/(:any)'] = 'admin/edit_user/$1';
$route['admin/forums_and_topics'] = 'admin/forums_and_topics';
$route['admin/options'] = 'admin/options';
$route['admin/emails'] = 'admin/emails';

/**
 * User routes
 */
$route['user'] = 'user/index';
$route['user/(:any)'] = 'user/index/$1';
$route['user/(:any)/edit'] = 'user/edit/$1';
$route['user/(:any)/delete'] = 'user/delete/$1';
$route['register'] = 'user/register';
$route['login'] = 'user/login';
$route['logout'] = 'user/logout';
$route['email_validation'] = 'user/email_validation';

/**
 * Forum routes
 */
$route['create_forum'] = 'forum/create_forum';
$route['(:any)/create_topic'] = 'forum/create_topic/$1';
//$route['/'] = 'forum/index';
$route['(:any)'] = 'forum/index/$1';
$route['(:any)/(:any)'] = 'forum/topic/$1/$2';
$route['(:any)/(:any)/reply'] = 'forum/create_post/$1/$2';

/**
 * Other routes
 */
$route['default_controller'] = 'install';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
