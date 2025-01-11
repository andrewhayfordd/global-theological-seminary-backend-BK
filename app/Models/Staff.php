<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $table = "tblstaff";
    protected $primaryKey = "transid";
    public $incrementing = false;
    const CREATED_AT = 'createdate';
    const UPDATED_AT = 'modifydate';

    protected $fillable = [
        "transid", "school_code", "branch_code","staffno", "title","fname", "mname", "lname", "gender",
        "dob", "marital_status", "phone", "email", "postal_address", "residential_address",
        "staff_type", "picture", "source", "import", "export", "deleted", "createdate",
        "createuser", "modifyuser", "modifydate",
    ];
}
