<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user=User::create([
            'name'=>'Alejandro',
            'email'=>'alejandro@gmail.com',
            'password'=>Hash::make('alejandro123'),
            'tipo'=>1,
            'created_at'=> date('Y-m-d H:i:s'),
            'updated_at'=> date('Y-m-d H:i:s')

        ]);

        $employee=Employee::create([
            'name'=>'Alejandro',
            'email'=>'alejandro@gmail.com',
            'clave'=>'alejandro123',
            'phone'=>'65656565',
            'user_id'=>$user->id,
            'user_reg'=>1,
            'tipo'=>1,
            'estado'=>1,
            'departments_id'=>1,
            'created_at'=> date('Y-m-d H:i:s'),
            'updated_at'=> date('Y-m-d H:i:s')

        ]);
    }
}
