<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transection extends Model
{
    use HasFactory;
    protected $fillable = [
        'tran_id',
        'tran_type',
        'rec_id',
        'tran_details',
        'currency_payed',
        'currency',
        'bank_id',
        'rate',
        'price',
        'tran_date',
        'status',
        'user_id',
        'fn',
    ];
}
