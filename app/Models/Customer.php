<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, HasApiTokens, HasRoles, SoftDeletes;

    protected $guard_name = 'api';

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
        'info',
        'tenant_id'
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
