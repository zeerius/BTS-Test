<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


$routes->post('register', 'AuthController::register');
$routes->post('login', 'AuthController::login');
$routes->group('auth', ['filter' => 'auth'], function (RouteCollection $routes) {
    $routes->get('checklist', 'ChecklistController::getAll');
    $routes->post('checklist', 'ChecklistController::create');
    $routes->delete('checklist/(:checkListId)', 'ChecklistController::delete');
    $routes->get('checklist/(:checkListId)/item', 'ChecklistController::getAllItem');
    $routes->post('checklist/(:checkListId)/item', 'ChecklistController::createItem');
    $routes->get('checklist/(:checkListId)/item/(:checkListItemId)', 'ChecklistController::getItem');
    $routes->put('checklist/(:checkListId)/item/(:checkListItemId)', 'ChecklistController::updateItemStatus');
    $routes->delete('checklist/(:checkListId)/item/(:checkListItemId)', 'ChecklistController::deleteItem');
    $routes->put('checklist/(:checkListId)/item/rename/(:checkListItemId)', 'ChecklistController::renameItem');
});