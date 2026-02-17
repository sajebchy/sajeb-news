<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            // News Permissions
            'create_news',
            'read_news',
            'update_news',
            'delete_news',
            'publish_news',
            'feature_news',
            'breaking_news',
            
            // Category Permissions
            'create_category',
            'read_category',
            'update_category',
            'delete_category',
            
            // User Management
            'create_user',
            'read_user',
            'update_user',
            'delete_user',
            
            // Advertisement
            'manage_advertisements',
            'view_ad_analytics',
            
            // Newsletter
            'manage_newsletter',
            'send_newsletter',
            
            // Push Notifications
            'send_push_notifications',
            
            // Analytics
            'view_analytics',
            
            // Settings
            'manage_settings',
            'manage_roles_permissions',
            'view_activity_logs',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $editor = Role::firstOrCreate(['name' => 'editor']);
        $reporter = Role::firstOrCreate(['name' => 'reporter']);
        $author = Role::firstOrCreate(['name' => 'author']);

        // Assign permissions to roles
        $superAdmin->syncPermissions(Permission::all());

        $admin->syncPermissions([
            'create_news',
            'read_news',
            'update_news',
            'delete_news',
            'publish_news',
            'feature_news',
            'breaking_news',
            'create_category',
            'read_category',
            'update_category',
            'delete_category',
            'create_user',
            'read_user',
            'update_user',
            'delete_user',
            'manage_advertisements',
            'view_ad_analytics',
            'manage_newsletter',
            'send_push_notifications',
            'view_analytics',
            'manage_settings',
            'manage_roles_permissions',
            'view_activity_logs',
        ]);

        $editor->syncPermissions([
            'create_news',
            'read_news',
            'update_news',
            'delete_news',
            'publish_news',
            'feature_news',
            'read_category',
            'view_analytics',
            'view_activity_logs',
        ]);

        $reporter->syncPermissions([
            'create_news',
            'read_news',
            'update_news',
            'read_category',
        ]);

        $author->syncPermissions([
            'create_news',
            'read_news',
            'update_news',
            'read_category',
        ]);
    }
}
