<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'tblattendance';
    protected $primaryKey = 'transid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'transid',
        'school_code',
        'branch_code',
        'acyear',
        'semester',
        'course_code',
        'student_code',
        'attendance_date',
        'status',
        'createuser',
        'createdate',
        'modifyuser',
        'modifydate',
    ];

    public $timestamps = false;
}

