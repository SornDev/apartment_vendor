<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccType extends Model
{
    use HasFactory; 
    protected $fillable = [
        'acc_type_name'
    ];
}
