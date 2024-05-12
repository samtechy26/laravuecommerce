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
        Product::create([
            "title"=>"Lorem, ipsum dolor sit amet ",
            "price"=> 19.83,
            "quantity"=>3,
            "category_id"=>1,
            "brand_id"=>1,
            "description"=>"Lorem ipsum dolor sit amet consectetur adipisicing elit. Impedit iure exercitationem voluptatem eligendi molestias vel quos nobis aut itaque praesentium, beatae corporis ab omnis ex veritatis placeat ut magni dolore."
        ]);
    }
}
