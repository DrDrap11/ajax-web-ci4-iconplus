<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setAutoRoute(true);
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
//$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
// $routes->get('/', 'Home::index');
// $routes->get('/test_view', 'Test_view::index');
// $routes->get('/employee', 'Employee::index');
// $routes->post('/employee/add', 'Employee::add');
$routes->get('/', 'Data::index', ['filter' => 'auth']);
$routes->get('/data', 'Data::index', ['filter' => 'auth']);
$routes->get('ajax-datatable', 'Employee::ajaxDataTables');
$routes->post('addData', 'Data::addData', ['as' => 'add.data']);
//  $routes->post('getEmployeeInfo','Employee::getEmployeeInfo',['as'=>'get.country.employee']);
$routes->get('getAllData', 'Data::getAllData');
$routes->post('updateEmployee', 'Employee::updateEmployee', ['as' => 'update.employee']);
$routes->post('deleteData', 'Data::deleteData', ['as' => 'delete.data']);

//Halaman daftar OLT
$routes->get('/olt', 'Olt::index', ['filter' => 'auth']);
$routes->get('/AllOlt', 'Olt::getAllOlt', ['filter' => 'auth']);
$routes->post('addOlt', 'Olt::addOlt', ['as' => 'add.olt']);
$routes->post('deleteOlt', 'Olt::deleteOlt', ['as' => 'delete.olt']);

$routes->get('/user', 'User::index', ['filter' => 'auth']);
$routes->get('getAllUser', 'User::getAllUser');
$routes->get('updateUser', 'User::updateUser');
$routes->get('deleteUser', 'User::deleteUser');

// Admin routes
$routes->group("admin", ["filter" => "auth"], function ($routes) {
    $routes->get("/", "AdminController::index");
});

// Impor route
$routes->post('import-csv', 'Employee::importCsvToDb');
$routes->post('/import', 'Data::importData');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
