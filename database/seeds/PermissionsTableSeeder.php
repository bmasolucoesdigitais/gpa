<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         //DB::table('permissions')->truncate();
         
         DB::table('permissions')->insert([
                [
                    'id'=> 1,
                    'name'=> 'Administer roles & permissions', 
                    'guard_name'=> 'web', 
                    
                ],
                [
                    'id'=> 2,
                    'name'=> 'master', 
                    'guard_name'=> 'web', 
                    
                ],
                [
                    'id'=> 3,
                    'name'=> 'Admin Users', 
                    'guard_name'=> 'web', 
                    
                ],
                [
                    'id'=> 4,
                    'name'=> 'G3 Company View', 
                    'guard_name'=> 'web', 
                    
                ],
                [
                    'id'=> 5,
                    'name'=> 'G3 Admin', 
                    'guard_name'=> 'web', 
                    
                ],
                [
                    'id'=> 6,
                    'name'=> 'G3 Company Edit', 
                    'guard_name'=> 'web', 
                    
                ],
                [
                    'id'=> 7,
                    'name'=> 'G3 Service View', 
                    'guard_name'=> 'web', 
                    
                ],
                [
                    'id'=> 8,
                    'name'=> 'G3 Service Edit', 
                    'guard_name'=> 'web', 
                    
                ],
                [
                    'id'=> 9,
                    'name'=> 'G3 Employees Edit', 
                    'guard_name'=> 'web', 
                    
                ],
                [
                    'id'=> 10,
                    'name'=> 'G3 Employees View', 
                    'guard_name'=> 'web', 
                    
                ],
                [
                    'id'=> 11,
                    'name'=> 'G3 Documents View', 
                    'guard_name'=> 'web', 
                    
                ],
                [
                    'id'=> 12,
                    'name'=> 'G3 Documents Edit', 
                    'guard_name'=> 'web', 
                    
                ],
                [
                    'id'=> 13,
                    'name'=> 'G3 Delivereds View', 
                    'guard_name'=> 'web', 
                    
                ],
                [
                    'id'=> 14,
                    'name'=> 'G3 Delivereds Edit', 
                    'guard_name'=> 'web', 
                    
                ],
            ]
        );

         
         DB::table('users')->insert([
            'name' => 'master',
            'email' => 'master@akinfo.com.br',
            'password' => bcrypt('M@sterP0w3rU23r'),
            'created_at'=> Date("Y-m-d"),
            'updated_at'=> Date("Y-m-d"),

         ]
               
        );

        DB::table('roles')->insert([
            'name' => 'Master',
            'guard_name' => 'web',
            'created_at'=> Date("Y-m-d"),
            'updated_at'=> Date("Y-m-d"),
         ]
               
        );

        DB::table('role_has_permissions')->insert([
            [
            'permission_id' => 1,
            'role_id' => 1,
            ],
            [
            'permission_id' => 2,
            'role_id' => 1,
            ],
            [
            'permission_id' => 3,
            'role_id' => 1,
            ],


         ]
               
        );


        DB::table('model_has_roles')->insert(
            [
            'role_id' => 1,
            'model_type' => 'App\User',
            'model_id' => 1,

            ]
               
        );

    }
}


