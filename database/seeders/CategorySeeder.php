<?php
// database/seeders/CategorySeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        // Only run if there are users
        $userId = DB::table('users')->first()?->id;
        
        if ($userId) {
            DB::table('categories')->insert([
                ['name' => 'Food', 'type' => 'expense', 'user_id' => $userId],
                ['name' => 'Transportation', 'type' => 'expense', 'user_id' => $userId],
                ['name' => 'Entertainment', 'type' => 'expense', 'user_id' => $userId],
                ['name' => 'Salary', 'type' => 'income', 'user_id' => $userId],
                ['name' => 'Freelance', 'type' => 'income', 'user_id' => $userId],
            ]);
        }
    }
}