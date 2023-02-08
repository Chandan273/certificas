<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, HasRoles, HasApiTokens, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'certificate_layout_id',
        'code',
        'name',
        'description',
        'date_from',
        'date_untill',
    ];
}
