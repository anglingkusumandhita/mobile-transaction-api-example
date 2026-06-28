<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [ [ 'name' => 'Buku Tulis', 'price' => 5000, ], [ 'name' => 'Pensil', 'price' => 3000, ], [ 'name' => 'Penghapus', 'price' => 2500, ], [ 'name' => 'Bolpoin', 'price' => 4000, ], [ 'name' => 'Penggaris', 'price' => 3500, ], [ 'name' => 'Spidol', 'price' => 8000, ], [ 'name' => 'Kertas A4', 'price' => 55000, ], [ 'name' => 'Map Plastik', 'price' => 6000, ], [ 'name' => 'Stapler', 'price' => 25000, ], [ 'name' => 'Isi Stapler', 'price' => 7000, ], ]; 
        
        foreach ($products as $product) { 
            Product::create($product); 
        }
    }
}
