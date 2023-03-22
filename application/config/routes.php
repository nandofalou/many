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
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
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
$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['dashboard'] = 'dashboard/index';

$route['auth/signin']['get'] = 'auth/signinview';
$route['auth/signin']['post'] = 'auth/signin';
$route['auth/signup']['get'] = 'auth/signupview';
$route['auth/signup']['post'] = 'auth/signup';
$route['auth/passwordrecovery']['get'] = 'auth/passwordrecoveryview';
$route['auth/passwordrecovery']['post'] = 'auth/passwordrecovery';
$route['auth/resetpassword']['get'] = 'auth/resetpassword';
$route['auth/resetpassword']['post'] = 'auth/changepass';
$route['auth/logout']['get'] = 'auth/logout';
$route['auth/api']['get'] = 'auth/api';

$route['colaborador']['get'] = 'colaborador/index';
$route['colaborador/edit/(:num)']['get'] = 'colaborador/view/$1';
$route['colaborador/edit/(:num)']['post'] = 'colaborador/save/$1';
$route['colaborador/add']['get'] = 'colaborador/new';
$route['colaborador/add']['post'] = 'colaborador/add';
$route['colaborador/changests']['post'] = 'colaborador/changests';
$route['colaborador/address/(:num)']['get'] = 'colaborador/address/$1';
$route['colaborador/alladdress/(:num)']['get'] = 'colaborador/alladdress/$1';
$route['colaborador/address']['post'] = 'colaborador/addaddress';
$route['colaborador/removeaddress/(:num)/(:num)']['get'] = 'colaborador/removeaddress/$1/$2';

$route['fornecedor']['get'] = 'fornecedor/index';
$route['fornecedor/edit/(:num)']['get'] = 'fornecedor/view/$1';
$route['fornecedor/edit/(:num)']['post'] = 'fornecedor/save/$1';
$route['fornecedor/add']['get'] = 'fornecedor/new';
$route['fornecedor/add']['post'] = 'fornecedor/add';

$route['produto']['get'] = 'produto/index';
$route['produto/edit/(:num)']['get'] = 'produto/view/$1';
$route['produto/edit/(:num)']['post'] = 'produto/save/$1';
$route['produto/add']['get'] = 'produto/new';
$route['produto/add']['post'] = 'produto/add';
$route['produto/changests']['post'] = 'produto/changests';

$route['loja']['get'] = 'loja/index';
$route['loja/order']['post'] = 'loja/createorder';
$route['loja/order/(:num)']['get'] = 'loja/order/$1';

$route['zipcode/(:any)']['get'] = 'zipcode/get/$1';


$route['api/v1/version']['get'] = 'api/version/index'; // POST
$route['api/v1/order']['get'] = 'api/order/index'; // GET
$route['api/v1/order/(:num)']['get'] = 'api/order/index/$1'; // GET
