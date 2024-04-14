<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocWork extends Model
{
    use HasFactory;
    protected $fillable = [
        'doc_cat',
        'customer_id',
        'customer_name',
        'customer_tel',
        'customer_address',
        'status',
    ];
}
