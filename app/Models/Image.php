<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    protected $fillable=[
        'imagen',
        'descripcion',
        'client_id',
        'estado',
        'employee_id'
    ];
}
