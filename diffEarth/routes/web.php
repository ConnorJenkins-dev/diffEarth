<?php

use Illuminate\Support\Facades\Route;

Route::get('/{any}', function () {
    return view('welcome'); // Ensure you have resources/views/app.blade.php
})->where('any', '.*');
