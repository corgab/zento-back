<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        if(env('APP_ENV') == 'production') {

            $this->call([
                UserSeeder::class,
                RoleSeeder::class,
                PermissionSeeder::class,
                RolePermissionSeeder::class,
                UserRoleSeeder::class,
            ]);
        } else {
            $this->call([
                // Production
                UserSeeder::class,
                RoleSeeder::class,
                PermissionSeeder::class,
                RolePermissionSeeder::class,
                UserRoleSeeder::class,
            
                TagSeeder::class,
                PostSeeder::class,
                NewsletterSeeder::class,
             ]);
        }

        
    }
}
