<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
})->name('login');

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('forgot.password');

Route::get('/verification-code', function () {
    return view('auth.verification-code');
})->name('verification.code');

Route::get('/reset-password', function () {
    return view('auth.reset-password');
})->name('reset.password');

Route::get('/capturist/dashboard', function () {
    return view('capturist.dashboard');
})->name('capturist.dashboard');

Route::get('/capturist/categories', function () {
    return view('capturist.categories');
})->name('capturist.categories');

Route::get('/capturist/categories/create', function () {
    return view('capturist.create-category');
})->name('capturist.categories.create');

Route::get('/capturist/assets', function () {
    return view('capturist.assets');
})->name('capturist.assets');

Route::get('/capturist/assets/show', function () {
    return view('capturist.show-asset');
})->name('capturist.assets.show');

Route::get('/capturist/assets/qr', function () {
    return view('capturist.generate-qr');
})->name('capturist.assets.qr');

Route::get('/capturist/assets/create', function () {
    return view('capturist.create-asset');
})->name('capturist.assets.create');