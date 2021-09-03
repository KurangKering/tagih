<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index', ['filter' => 'authFilter']);


$routes->group('/', ['filter' => 'authFilter:mahasiswa'], function($routes) {
	$routes->get('tagihan/data-mahasiswa', 'TagihanController::dataMahasiswa');
	$routes->get('tagihan/data-mahasiswa-json-dt', 'TagihanController::dataMahasiswaJsonDT');
	$routes->get('tagihan/info-mahasiswa', 'TagihanController::infoMahasiswa');
	$routes->post('tagihan/bayar', 'TagihanController::bayar');

});

$routes->group('/', ['filter' => 'authFilter:bendahara'], function($routes) {
	$routes->get('mahasiswa/data', 'MahasiswaController::data');
	$routes->get('mahasiswa/data-json-dt', 'MahasiswaController::dataJsonDT');
	$routes->get('mahasiswa/show', 'MahasiswaController::show');
	$routes->post('mahasiswa/create-update', 'MahasiswaController::createUpdate');
	$routes->post('mahasiswa/create', 'MahasiswaController::create');
	$routes->post('mahasiswa/update', 'MahasiswaController::update');
	$routes->get('mahasiswa/delete', 'MahasiswaController::delete');

	$routes->get('periode', 'PeriodeController::index');
	$routes->get('periode/data', 'PeriodeController::data');
	$routes->get('periode/data-json-dt', 'PeriodeController::dataJsonDT');
	$routes->get('periode/delete', 'PeriodeController::delete');
	$routes->post('periode/create-update', 'PeriodeController::createUpdate');

	$routes->get('mahasiswa-periode/data', 'MahasiswaPeriodeController::data');
	$routes->get('mahasiswa-periode/data-json-dt', 'MahasiswaPeriodeController::dataJsonDT');

	$routes->get('tagihan/data-bendahara', 'TagihanController::dataBendahara');
	$routes->get('tagihan/data-bendahara-json-dt', 'TagihanController::dataBendaharaJsonDT');
	$routes->get('tagihan/delete', 'TagihanController::delete');
	$routes->get('tagihan/tambah', 'TagihanController::tambah');
	$routes->post('tagihan/cek', 'TagihanController::cek');
	$routes->post('tagihan/create', 'TagihanController::create');

});




$routes->group('/', ['filter' => 'authFilter:pimpinan'], function($routes) {
	$routes->get('tagihan/data-pimpinan', 'TagihanController::dataPimpinan');
	$routes->get('tagihan/data-pimpinan-json-dt', 'TagihanController::dataPimpinanJsonDT');
	$routes->get('tagihan/grafik-pimpinan', 'TagihanController::grafikPimpinan');

});



$routes->group('/', ['filter' => 'authFilter'], function($routes) {

	$routes->get('mahasiswa', 'MahasiswaController::index');
	$routes->get('mahasiswa/edit', 'MahasiswaController::edit');
	$routes->get('mahasiswa-periode', 'MahasiswaPeriodeController::index');
	$routes->get('tagihan', 'TagihanController::index');
	$routes->get('periode', 'PeriodeController::index');
});

$routes->get('auth/login', 'AuthController::login', ['filter' => 'noAuthFilter']);
$routes->post('auth/process-login', 'AuthController::loginProcess', ['filter' => 'noAuthFilter']);
$routes->get('auth/process-logout', 'AuthController::logoutProcess', ['filter' => 'authFilter']);




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
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
