<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\TransactionDetailController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post( '/login', [AuthController::class, 'login'] );

Route::middleware('auth:sanctum') ->group(function (): void { 
    Route::post( '/logout', [AuthController::class, 'logout'] ); 
    /* * PRODUCTS: READ ONLY */ 
    Route::get( '/products', [ProductController::class, 'index'] ); /* * TRANSACTIONS */ 
    
    Route::get( '/transactions', [TransactionController::class, 'index'] ); 
    
    Route::post( '/transactions', [TransactionController::class, 'store'] ); 
    
    Route::get( '/transactions/{transaction}', [TransactionController::class, 'show'] ); /* * CRUD TRANSACTION DETAILS * * CREATE: * POST /transactions/{id}/details * * READ: * GET /transactions/{id} * * UPDATE: * PUT /transaction-details/{id} * * DELETE: * DELETE /transaction-details/{id} */ 
    
    Route::post( '/transactions/{transaction}/details', [ TransactionDetailController::class, 'store', ] ); 
    
    Route::put( '/transaction-details/{transactionDetail}', [ TransactionDetailController::class, 'update', ] ); 
    
    Route::delete( '/transaction-details/{transactionDetail}', [ TransactionDetailController::class, 'destroy', ] ); 
    });
