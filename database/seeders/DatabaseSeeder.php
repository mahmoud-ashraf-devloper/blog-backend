<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    private function rolesPermissions()
    {
        $admin = Role::create(['name' => 'Admin']);
        $user = Role::create(['name' => 'User']);
        $author = Role::create(['name' => 'Author']);
    


        $AllPermissions = [
            //0
            'create article',
            //1
            'edit articles',
            // 2
            'create author',
            // 3
            'edit author',
            // 4
            'delete author',
            // 5
            'create category',
            // 6
            'edit category',
            // 7
            'delete category',
            // 8
            'add comment',
            // 9
            'delete comment',
            // 10
            'create role',
            // 11
            'delete role',
            // 12
            'edit role',
            // 13
            'get all roles',
        ];
    
        $authorPermissions = [
            $AllPermissions[0],
            $AllPermissions[2],
        ];
    
        $userPermissions = [
            $AllPermissions[9],
        ];
    
        // Seeding all permissions to the database
        foreach ($AllPermissions as $permission){
            Permission::create(['name' => $permission]);
        }
    
        // Giving Admin All Permissions
        foreach ($AllPermissions as $permission){
            $admin->givePermissionTo($permission);
        }
    
        // Giving Author it's permissons
        foreach ($authorPermissions as $permission){
            $author->givePermissionTo($permission);
        }
    }
    public function run()
    {
        $this->rolesPermissions();
        $roles = [
            'admin' => [
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
            ],
            'author' => [
                'name' => 'Author',
                'email' => 'author@author.com',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
            ],
            'user' => [
                'name' => 'User',
                'email' => 'user@user.com',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
            ],
        ];

        User::create($roles['admin'])->assignRole('Admin');
        User::create($roles['author'])->assignRole('Author');
        User::create($roles['user'])->assignRole('User');

        User::factory(7)->create();
        \App\Models\Category::factory(10)->create();
        \App\Models\Author::factory(10)->create();
        \App\Models\Article::factory(10)->create();
        \App\Models\Comment::factory(10)->create();
    }
}
