<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $some = 'asd';

    $l = 1;
    return view('welcome');
});
