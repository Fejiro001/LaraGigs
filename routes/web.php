<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ListingController;

// Common Resource Routes:
// index - Show all listings
// show - Show single listing
// create - Show form to create new listing
// store - Store new listing
// edit - Show form to edit listing
// update - Update listing
// destroy - Delete listing 

// All Listings
Route::get('/', [ListingController::class, 'index']);

// Show Create Form
Route::get('/listings/create', [ListingController::class, 'create']);

// Store Listing Data
Route::post('/listings', [ListingController::class, 'store']);



// Single Listing
Route::get('/listings/{listing}', [ListingController::class, 'show']);
