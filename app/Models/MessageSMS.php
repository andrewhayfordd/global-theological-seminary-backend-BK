<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageSMS extends Model
{
    use HasFactory;
    const UPDATED_AT = "modifydate";
    const CREATED_AT = "createdate";
    
    protected $table = "tblsms_sent";
    protected $primaryKey = "transid";
    public $incrementing = false;
}
