<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
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
        'info'
    ];
}
