<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory; 
    
    protected $fillable = [ 'name', 'price', ]; 
    protected function casts(): array 
    { 
        return [ 'price' => 'integer', ]; 
    } 
    
    public function transactionDetails(): HasMany 
    { 
        return $this->hasMany( TransactionDetail::class ); 
    }
}
