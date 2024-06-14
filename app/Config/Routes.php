<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->post('register', 'AuthController::register');
$routes->post('login', 'AuthController::login');
