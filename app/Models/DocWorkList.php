<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocWorkList extends Model
{
    use HasFactory;
    protected $fillable = [
        'doc_work_id',
        'doc_id',
        'doc_copy',
        'notice',
        'status',
    ];

}
