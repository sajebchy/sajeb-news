<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles and permissions first
        $this->call(RoleAndPermissionSeeder::class);

        // Create test admin user
        $testAdmin = User::create([
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('12345'),
        ]);
        $testAdmin->assignRole('super-admin');

        // Create admin user
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@sajeb-news.local',
        ]);
        $admin->assignRole('super-admin');

        // Create sample editor
        $editor = User::factory()->create([
            'name' => 'Editor User',
            'email' => 'editor@sajeb-news.local',
        ]);
        $editor->assignRole('editor');

        // Create sample reporter
        $reporter = User::factory()->create([
            'name' => 'Reporter User',
            'email' => 'reporter@sajeb-news.local',
        ]);
        $reporter->assignRole('reporter');
    }
}
