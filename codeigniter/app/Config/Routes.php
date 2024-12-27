<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->match(['GET', 'POST'], '/signup', 'Home::Signup');
$routes->match(['GET', 'POST'], '/login', 'Home::Login');
$routes->match(['GET', 'POST'], '/logout', 'Home::Logout');


$routes->match(['GET', 'POST'], '/users', 'Home::Users');
$routes->match(['GET', 'POST'], '/adduser', 'Home::adduser');
$routes->post('/updateuser', 'Home::updateUser');
$routes->get('/deleteuser/(:num)', 'Home::deleteUser/$1');
$routes->delete('/deleteuser/(:num)', 'Home::deleteUser/$1');


$routes->match(['GET', 'POST'], '/campaigns', 'Home::campaigns');
$routes->match(['GET', 'POST'], '/addcampaign', 'Home::addcampaign');
$routes->post('/updatecampaign', 'Home::updatecampaign');
$routes->get('/deleteCampaign/(:num)', 'Home::deleteCampaign/$1');
$routes->delete('/deleteCampaign/(:num)', 'Home::deleteCampaign/$1');


$routes->match(['GET', 'POST'], '/chats', 'Home::chats');


$routes->get('/accesslevel', 'Home::accessLevel');
$routes->post('/update-role/(:num)', 'Home::updateRole/$1');


