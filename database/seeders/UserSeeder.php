<?php

namespace Database\Seeders;

use App\Models\Dialect;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Rafiq',
            'ar_name' => 'رفيق',
            'username' => 'palweb.testing',
            'email' => 'rafiq@palweb.app',
            'email_verified_at' => now(),
            'avatar' => 'palweb01.jpg',
            'home' => 'Palestine',
            'bio' => 'Thank you for helping to build PalWeb!',
            'dialect_id' => Dialect::find(1)->id,
            'private' => false,
            'language' => 'en',
            'password' => Hash::make('12345678'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        User::factory(49)->create();
    }
}
