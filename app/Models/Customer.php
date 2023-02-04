<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'number',
        'name',
        'contact',
        'organisation_number',
        'email',
        'www',
        'phone',
        'address',
        'zip',
        'city',
        'country',
        'info',
    ];
}
