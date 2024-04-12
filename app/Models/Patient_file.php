<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient_file extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_id','patient_id','test_result','X_ray_result'
    ];
    
    protected $casts =[
        'test_result'=>'array',
        'X_ray_result'=>'array'
    ];
}