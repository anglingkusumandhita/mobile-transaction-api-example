<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    use HasFactory; 
    
    protected $fillable = [ 'user_id', 'transaction_code', 'transaction_date', 'total_amount', ]; 
    
    protected function casts(): array { return [ 'transaction_date' => 'datetime', 'total_amount' => 'integer', ]; } 
    
    public function user(): BelongsTo 
    { 
        return $this->belongsTo( User::class ); 
    } 
    
    public function details(): HasMany 
    { 
        return $this->hasMany( TransactionDetail::class )->orderBy('id'); 
    }
}
