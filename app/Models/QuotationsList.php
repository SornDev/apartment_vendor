<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationsList extends Model
{
    use HasFactory;
    protected $fillable = [
        'quo_id',
        'quo_name',
        'qty',
        'price',
    ];
}
