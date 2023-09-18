<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('clients')->insert([
            'name' => 'Arturo',
            'apellidos'=>'Gomez Bolaños',
            'telefono'=>'4645756',
            'observacion'=>'Es un  cliente',
            'estado'=>1,
            'employee_id'=>1
        ]);

        DB::table('clients')->insert([
            'name' => 'Milhouse',
            'apellidos'=>'Van Houten',
            'telefono'=>'8756454',
            'observacion'=>'Es un  cliente esprinfield',
            'estado'=>1,
            'employee_id'=>1
        ]);
        DB::table('clients')->insert([
            'name' => 'Martin',
            'apellidos'=>'Princs',
            'telefono'=>'76767656',
            'observacion'=>'Es un  cliente esprinfield',
            'estado'=>1,
            'employee_id'=>1
        ]);
    }
}
