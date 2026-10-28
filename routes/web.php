<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');

Route::view('/tasks', 'tasks.index')->name('tasks.index');
Route::view('/tasks/create', 'tasks.create')->name('tasks.create');
Route::view('/tasks/{task}', 'tasks.show')->whereNumber('task')->name('tasks.show');
Route::view('/tasks/{task}/edit', 'tasks.edit')->whereNumber('task')->name('tasks.edit');
