<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
    use HasFactory;

    protected $table = 'tbltime_table';
    protected $primaryKey = 'transid';
    protected $fillable = [
        'student_id',
        'course_code',
        'day',
        'start_time',
        'end_time',
        'location',
    ];

    public $timestamps = false;
}


