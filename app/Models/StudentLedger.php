<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentLedger extends Model
{
    use HasFactory;

    const UPDATED_AT = "modifydate";
    const CREATED_AT = "createdate";
    
    protected $table = "tblledger_student";
    protected $primaryKey = "transid";
    public $incrementing = false;

    protected $fillable = [
        "credit","ref_code","transid","student_no","createdate",
        "acyear","acterm","grade","branch_code","item_code","debit","type","balance",
        "deleted", "createuser", "modifyuser", "modifydate","school_code"
    ];
}
