<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'certificate_layout_id',
        'code',
        'name',
        'description',
        'date_from',
        'date_untill',
        'info',
    ];
}
