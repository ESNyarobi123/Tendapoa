<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->upsert([
            ['name'=>'Inside Home Cleaning','slug'=>'inside-home'],
            ['name'=>'Outside Home Cleaning','slug'=>'outside-home'],
            ['name'=>'Office Cleaning','slug'=>'office'],
            ['name'=>'Post-Construction','slug'=>'post-construction'],
        ], ['slug'], ['name']);
    }
}
