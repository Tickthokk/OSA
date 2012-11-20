<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

## Admin Section
$route['admin'] = 'admin/admin/dashboard';
$route['admin/(:any)'] = 'admin/admin/$1';

## USERS
$route['user/login'] = 'user/login';
$route['user/logout'] = 'user/logout';
$route['user/register'] = 'user/register';
$route['user/forgotten-password'] = 'user/forgotten_password';
$route['user/achievements/(:any)'] = 'user/achievements/$1';
$route['user/comments/(:any)'] = 'user/comments/$1';
$route['user/created_achievements/(:any)'] = 'user/created_achievements/$1';
$route['user/(:any)'] = 'user/view/$1';
$route['user'] = 'user/main';

## GAME
$route['game/(:num)'] = 'game/index/$1';
$route['game/(:any)'] = 'game/$1';

$route['search'] = 'search/main';
$route['images/(:any)'] = 'images/view/$1';
$route['games/(:any)'] = 'games/view/$1';
$route['games'] = 'games/view';

$route['create/(:any)'] = 'create/$1';

$route['flag/(:any)'] = 'flag/run/$1';

# Achievements - Specific
$route['achievement/(:num)'] = 'achievement/view/$1';

# Achievements - Catchall
$route['achievement/(:any)'] = 'achievement/$1';

# Migrations
$route['migrate'] = 'migrate';

## DEFAULTS
$route['(:any)'] = 'pages/view/$1';

$route['default_controller'] = "pages/view";

/* End of file routes.php */
/* Location: ./application/config/routes.php */