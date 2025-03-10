<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_name',
        'company_address',
        'company_tel',
        'company_email',
        'company_logo',
        'printer_default',
        'tax_value',
    ];
}
