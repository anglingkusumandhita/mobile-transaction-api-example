<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function index( Request $request ): JsonResponse { 
        $transactions = Transaction::query() 
        ->where( 'user_id', $request->user()->id ) 
        ->with([ 'user:id,name,email', 'details.product:id,name,price', ]) 
        ->latest('id') 
        ->get(); 
        
        return response()->json( $transactions );
    } 
    
    /* * MEMBUAT HEADER TRANSAKSI BARU */ 
    
    public function store( Request $request ): JsonResponse { 
        $transaction = Transaction::create([ 'user_id' => $request->user()->id, 'transaction_code' => 'TRX-'.strtoupper( (string) Str::ulid() ), 'transaction_date' => now(), 'total_amount' => 0, ]); 
        
        $transaction->load([ 'user:id,name,email', 'details.product:id,name,price', ]); 
        
        return response()->json([ 'message' => 'Transaksi berhasil dibuat.', 'data' => $transaction, ], 201); 
    } 
    
    /* * MEMBACA SATU TRANSAKSI BESERTA DETAIL */ 
    
    public function show( Request $request, Transaction $transaction ): JsonResponse {
         $this->ensureOwnedByUser( $transaction, $request ); 
         $transaction->load([ 'user:id,name,email', 'details.product:id,name,price', ]); return response()->json( $transaction ); 
    } 
    
    private function ensureOwnedByUser( Transaction $transaction, Request $request ): void {
         abort_if( $transaction->user_id !== $request->user()->id, 403, 'Anda tidak berhak mengakses transaksi ini.' ); 
    }
}
