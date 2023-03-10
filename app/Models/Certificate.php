<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

class Certificate extends Model
{
    use HasFactory, HasApiTokens, HasRoles, SoftDeletes;

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    protected $fillable = [
        'student_id',
        'course_id',
        'certificate_layout_id',
        'description',
        'valid_from',
        'valid_untill',
        'info',
    ];
}
