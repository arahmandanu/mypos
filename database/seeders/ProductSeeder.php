<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Topping;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            'Milk Tea' => ['small' => 15000, 'medium' => 18000, 'big' => 20000],
            'Thai Tea' => ['small' => 16000, 'medium' => 19000, 'big' => 21000],
            'Chocolate' => ['small' => 17000, 'medium' => 20000, 'big' => 22000],
            'Matcha Latte' => ['small' => 18000, 'medium' => 21000, 'big' => 23000],
        ];

        foreach ($products as $name => $prices) {
            $product = Product::firstOrCreate(['name' => $name], ['category' => 'Beverage']);

            foreach ($prices as $size => $price) {
                $product->variants()->firstOrCreate(['size' => $size], ['price' => $price]);
            }
        }

        $toppings = [
            'Boba' => 5000,
            'Pudding' => 6000,
            'Cheese Foam' => 7000,
            'Grass Jelly' => 5000,
            'Barbeque' => 6000,
        ];

        foreach ($toppings as $name => $price) {
            Topping::firstOrCreate(['name' => $name], ['price' => $price]);
        }
    }
}
