<?php

use Illuminate\Support\Facades\Route;

// API routes will be added in Phase 6
Route::get('/', function () {
    return response()->json(['message' => 'Marketplace API v1', 'status' => 'ok']);
});
