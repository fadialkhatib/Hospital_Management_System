<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Xrayqueue extends Model
{
    use HasFactory;
    protected $fillable = ['patient_id'];
    public $timestamps =true;

}
