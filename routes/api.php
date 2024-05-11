<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Buyer\BuyerController;
use App\Http\Controllers\Seller\SellerController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Transaction\TransactionController;

 


Route::resource('users', UserController::class)->except(['create', 'edit']);
Route::resource('transactions', TransactionController::class)->only(['index', 'show']);
Route::resource('sellers', SellerController::class)->only(['index', 'show']);
Route::resource('products', ProductController::class)->only(['index', 'show']);
Route::resource('categories', CategoryController::class)->except(['create', 'edit']);
Route::resource('buyers', BuyerController::class)->only(['index', 'show']);
