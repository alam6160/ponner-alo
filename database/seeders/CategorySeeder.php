<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cat1 = new \App\Models\Category();
        $cat1->slug = 'brands';
        $cat1->cat_title = 'Brands';
        $cat1->parent_id = NULL;
        $cat1->save();
    }
}
