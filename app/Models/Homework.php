<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Homework extends Model
{
    use HasFactory;

    protected $table = 'tblhomework';
    protected $primaryKey = 'transid';
    public $incrementing = false;

    protected $fillable = [
        'school_code', 'homework_recipient', 'homework_title', 'homework_details',
        'course_recipient', 'file_path', 'posted_by', 'date_posted', 'date_start', 'date_end', 
        'modifydate'
    ];
}
