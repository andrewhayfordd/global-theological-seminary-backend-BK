<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    const UPDATED_AT = "modifydate";
    const CREATED_AT = "createdate";
    
    protected $table = "tblprog";
    protected $primaryKey = "transid";
    public $incrementing = false;

    protected $fillable = [
        'transid','school_code','prog_duration','prog_code',
        'prog_type','prog_desc','branch_code','source',
        'deleted','createdate','createuser','modifydate','modifyuser'
    ];
}
