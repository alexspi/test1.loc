<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Super Admin',
                'email' => 'admin@admin.loc',
                'role' => 1,
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
            ],
            [
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'role' => 2,
                'email_verified_at' => now(),
                'password' => Hash::make(env('PASS1')),
                'remember_token' => Str::random(10),
            ],
            [
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'role' => 2,
                'email_verified_at' => now(),
                'password' => Hash::make(env('PASS2')),
                'remember_token' => Str::random(10),
            ],
            [
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'role' => 3,
                'email_verified_at' => now(),
                'password' => Hash::make(env('PASS3')),
                'remember_token' => Str::random(10),
            ],
            [
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'role' => 3,
                'email_verified_at' => now(),
                'password' => Hash::make(env('PASS4')),
                'remember_token' => Str::random(10),
            ]
        ]);

        DB::table('roles')->insert([
            [
                'name' => 'superadmin'
            ],
            [
                'name' => 'manager'
            ],
            [
                'name' => 'applicant'
            ],

        ]);
    }
}
