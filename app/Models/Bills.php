<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bills extends Model
{
    use HasFactory;
    const UPDATED_AT = "modifydate";
    const CREATED_AT = "createdate";
    
    protected $table = "tblbills";
    protected $primaryKey = "transid";
    public $incrementing = false;

    protected $fillable = [
        "school_code", "bill_code","bill_desc","transid","student_no","createdate",
        "acyear","acterm","item","amount","branch_code","sem_code",
        "deleted", "createuser", "modifyuser", "modifydate","school_code"
    ];
}
