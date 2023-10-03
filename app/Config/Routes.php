<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
//================= CONFIG ROUTES CROSS-ORIGIN ======================
$routes->options('(:any)', 'Home::options'); //one options method for all routes.
$routes->options('(:any)', 'SupplyController::options'); //one options method for all routes.
$routes->options('(:any)', 'ProductController::options'); //one options method for all routes.
$routes->options('(:any)', 'RecipeController::options'); //one options method for all routes.

//================= REPORTS ROUTES =====================
$routes->get('/', 'Home::index');
$routes->get('/report', 'Home::generarPDF');
$routes->get('/report-detail', 'Home::generarPDFDetails');
$routes->get('/report-supply', 'Home::generarPDFSupply');

//================= SUPPLY ROUTES ======================
$routes->get('/supply-list', 'SupplyController::supplyList',['filter' => 'authFilter']);
$routes->get('/supply-list-name', 'SupplyController::supplyListByName',['filter' => 'authFilter']);
$routes->post('/supply-save', 'SupplyController::saveSupply',['filter' => 'authFilter']);
$routes->post('/supply-edit', 'SupplyController::editSupply',['filter' => 'authFilter']);

//================= PRODUCT ROUTES ======================
$routes->post('/product-save', 'ProductController::saveProduct',['filter' => 'authFilter']);
$routes->get('/product-list', 'ProductController::productList',['filter' => 'authFilter']);
$routes->get('/product-list-name', 'ProductController::productListByName',['filter' => 'authFilter']);
$routes->post('/product-edit', 'ProductController::editProduct',['filter' => 'authFilter']);

//================= RECIPE ROUTES ======================
$routes->post('/recipe-save', 'RecipeController::saveRecipe',['filter' => 'authFilter']);
$routes->get('/recipe-list', 'RecipeController::recipeList',['filter' => 'authFilter']);
$routes->post('/recipe-delete', 'RecipeController::deleteRecipe',['filter' => 'authFilter']);





//================= LOGIN ROUTES ======================
$routes->post('/login', 'Auth::create');


//JWT
$routes->resource('api/auth', ['controller' => 'Auth']);
$routes->resource('api/home', ['controller' => 'Home']);
