<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;
    protected $fillable = [
        'rec_id',
        'doc_work_id',
        'customer_id',
        'quo_id',
        'customer_name',
        'customer_tel',
        'customer_address',
        'rec_discount',
        'rec_vat',
        'status',
    ];
}
