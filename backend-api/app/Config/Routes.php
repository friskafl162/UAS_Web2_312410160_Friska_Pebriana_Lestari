<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->options('(:any)', static function () {
    return response();
});

$routes->get('/buku', 'Buku::index');
$routes->post('/buku', 'Buku::create', ['filter' => 'auth']);
$routes->put('/buku/(:num)', 'Buku::update/$1', ['filter' => 'auth']);
$routes->delete('/buku/(:num)', 'Buku::delete/$1', ['filter' => 'auth']);

$routes->get('/peminjaman', 'Peminjaman::index');
$routes->post('/peminjaman', 'Peminjaman::create');
$routes->put('/peminjaman/(:num)', 'Peminjaman::update/$1');
$routes->delete('/peminjaman/(:num)', 'Peminjaman::delete/$1');

$routes->get('/', 'Home::index');
$routes->get('/kategori', 'Kategori::index');



$routes->post('/login', 'Auth::login');