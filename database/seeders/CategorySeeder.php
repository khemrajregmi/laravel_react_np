<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CategorySeeder extends Seeder
{

    private const TABLE = 'categories';
    private const CATEGORY_NAME = 'name';
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $category =['Business','Health','Science','Sports','Technology'];
        foreach ($category as $value){
            DB::table(self::TABLE)->insert([
                self::CATEGORY_NAME => $value,
            ]);
        }
    }
}
