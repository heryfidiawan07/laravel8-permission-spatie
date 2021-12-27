<?php

use Illuminate\Support\Facades\{Route, Auth};
use App\Http\Controllers\{
    HomeController, RoleController, UserController
};

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

// User & Role - Permission
Route::resource('role', RoleController::class, ['except' => ['create','edit']]);
Route::resource('user', UserController::class, ['except' => ['create','edit']]);

// Set Super Admin
Route::get('set-super-admin', [UserController::class, 'setSuperAdmin']);