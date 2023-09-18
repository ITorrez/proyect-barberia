<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'email',
        'clave',
        'phone',
        'user_id',
        'user_reg',
        'tipo',
        'estado',
        'departments_id'
    ];
}
