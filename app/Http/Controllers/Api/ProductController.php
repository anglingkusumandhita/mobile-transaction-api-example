<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(): JsonResponse { 
        $products = Product::query() ->orderBy('name') 
        ->get([ 'id', 'name', 'price', ]); 
        
        return response()->json($products); 
    }
}
