<?php

use Illuminate\Database\Seeder;
use App\Category;

class CategoryTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'name' => '',
            'amount' => 100
        ]);
        Category::create([
            'name' => '',
            'amount' => 300
        ]);
        Category::create([
            'name' => '',
            'amount' => 500
        ]);
        Category::create([
            'name' => '',
            'amount' => 1000
        ]);
        Category::create([
            'name' => '',
            'amount' => 5000
        ]);
        Category::create([
            'name' => '',
            'amount' => 10000
        ]);
    }
}
