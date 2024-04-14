<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiptList extends Model
{
    use HasFactory;
    protected $fillable = [
        'rec_id',
        'rec_name',
        'qty',
        'price',
    ];
}
