<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;
    const UPDATED_AT = "modifydate";
    const CREATED_AT = "createdate";
    
    protected $table = "tblschool";
    protected $primaryKey = "school_code";
    public $incrementing = false;
}
