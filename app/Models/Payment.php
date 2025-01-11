<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    const UPDATED_AT = "modifydate";
    const CREATED_AT = "createdate";
    
    protected $table = "tblpayment";
    protected $primaryKey = "transid";
    public $incrementing = false;

    protected $fillable = [
        "student_no","school_code","transid","branch","semester","createdate","total_paid",
        "payment_date","payment_type","receipt_no","cheque_bank","cheque_no","amount","cur_balance",
        "deleted", "createuser", "modifyuser", "modifydate","school_code"
    ];
}
