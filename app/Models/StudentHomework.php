<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentHomework extends Model
{
    use HasFactory;

    protected $table = 'tblstudent_homework';
    protected $primaryKey = 'transid';
    public $incrementing = false;

    protected $fillable = [
        'school_code', 'homework_title',
        'course_recipient', 'file_path', 'posted_by', 'date_posted', 
        'modifydate'
    ];
}