<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


$routes->post('register', 'AuthController::register');
$routes->post('login', 'AuthController::login');
$routes->group('', ['filter' => 'auth'], function (RouteCollection $routes) {
    $routes->get('checklist', 'ChecklistController::getAll');
    $routes->post('checklist', 'ChecklistController::create');
    $routes->delete('checklist/(:num)', 'ChecklistController::delete/$1');
    $routes->get('checklist/(:num)/item', 'ChecklistController::getAllItem/$1');
    $routes->post('checklist/(:num)/item', 'ChecklistController::createItem/$1');
    $routes->get('checklist/(:num)/item/(:num)', 'ChecklistController::getItem/$1/$2');
    $routes->put('checklist/(:num)/item/(:num)', 'ChecklistController::updateItemStatus/$1/$2');
    $routes->delete('checklist/(:num)/item/(:num)', 'ChecklistController::deleteItem/$1/$2');
    $routes->put('checklist/(:num)/item/rename/(:num)', 'ChecklistController::renameItem/$1/$2');
});