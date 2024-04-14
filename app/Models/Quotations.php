<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotations extends Model
{
    use HasFactory;
    protected $fillable = [
        'quo_id',
        'doc_work_id',
        'customer_id',
        'customer_name',
        'customer_tel',
        'customer_address',
        'status',
    ];
}
