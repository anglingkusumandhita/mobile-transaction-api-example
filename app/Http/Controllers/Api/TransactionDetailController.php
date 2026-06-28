<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionDetailController extends Controller
{
    public function store( Request $request, Transaction $transaction ): JsonResponse {
        $this->ensureTransactionOwnedByUser( $transaction, $request ); 
        $validated = $request->validate([ 'product_id' => [ 'required', 'integer', 'exists:products,id', ], 'quantity' => [ 'required', 'integer', 'min:1', ], ]); 
        
        $result = DB::transaction( function () use ( $transaction, $validated ): array { 
            /* * Harga harus diambil dari database, * bukan dari request Android. */ $product = Product::findOrFail( $validated['product_id'] ); 
            $quantity = (int) $validated['quantity']; 
            $price = (int) $product->price; $subtotal = $price * $quantity; $detail = $transaction ->details() 
            ->create([ 'product_id' => $product->id, 'quantity' => $quantity, 'price' => $price, 'subtotal' => $subtotal, ]); 
            
            $total = $this->recalculateTotal( $transaction ); 
            
            $detail->load( 'product:id,name,price' ); 
            
            return [ 'detail' => $detail, 'total_amount' => $total, ]; 
            } ); 
            
        return response()->json([ 'message' => 'Detail transaksi berhasil ditambahkan.', 'data' => $result['detail'], 'total_amount' => $result['total_amount'], ], 201); 
        } 
        
        /* * UPDATE QUANTITY DETAIL TRANSAKSI */ 
        
        public function update( Request $request, TransactionDetail $transactionDetail ): JsonResponse { 
            $transaction = $transactionDetail->transaction; 
            
            $this->ensureTransactionOwnedByUser( $transaction, $request ); 
            
            $validated = $request->validate([ 'quantity' => [ 'required', 'integer', 'min:1', ], ]); 
            
            $result = DB::transaction( function () use ( $transaction, $transactionDetail, $validated ): array { 
                $quantity = (int) $validated['quantity']; /* * Menggunakan harga yang sudah tersimpan * pada detail transaksi. */ 
                
                $subtotal = $transactionDetail->price * $quantity; 
                
                $transactionDetail->update([ 'quantity' => $quantity, 'subtotal' => $subtotal, ]); 
                
                $total = $this->recalculateTotal( $transaction ); 
                
                $transactionDetail->load( 'product:id,name,price' ); 
                
                return [ 'detail' => $transactionDetail, 'total_amount' => $total, ]; } ); 
                
                return response()->json([ 'message' => 'Detail transaksi berhasil diperbarui.', 'data' => $result['detail'], 'total_amount' => $result['total_amount'], ]); 
        } /* * DELETE DETAIL TRANSAKSI */ 
        
        public function destroy( Request $request, TransactionDetail $transactionDetail ): JsonResponse { 
            $transaction = $transactionDetail->transaction; 
            
            $this->ensureTransactionOwnedByUser( $transaction, $request ); 
            
            $total = DB::transaction( function () use ( $transaction, $transactionDetail ): int { 
                $transactionDetail->delete(); 
                
                return $this->recalculateTotal( $transaction ); } 
                ); 
                
                return response()->json([ 'message' => 'Detail transaksi berhasil dihapus.', 'total_amount' => $total, ]);
        } /* * MENGHITUNG ULANG TOTAL TRANSAKSI */ 
        
        private function recalculateTotal( Transaction $transaction ): int { 
            $total = (int) $transaction 
            ->details() 
            ->sum('subtotal'); 
            
            $transaction->update([ 'total_amount' => $total, ]); 
            
            return $total; 
        } /* * MEMASTIKAN TRANSAKSI MILIK USER LOGIN */ 
        
        private function ensureTransactionOwnedByUser( Transaction $transaction, Request $request ): void { 
            abort_if( $transaction->user_id !== $request->user()->id, 403, 'Anda tidak berhak mengubah transaksi ini.' ); 
        }
}
