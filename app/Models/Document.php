<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;
    protected $fillable = [
        'cat_id',
        'doc_name',
        'image',
        'doc_example',
        'doc_form',
        'notice'
    ];
}
