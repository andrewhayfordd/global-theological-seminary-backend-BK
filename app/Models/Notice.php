<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    const UPDATED_AT = "modifydate";
    const CREATED_AT = "createdate";

    protected $table = "tblnotice";
    protected $primaryKey = "transid";
    public $incrementing = false;

    protected $fillable = [
        "notice_code", "notice_type", "transid", "acyear", "term", "posted_by",
        "notice_title", "notice_recipient", "notice_details", "date_posted", "date_start", "date_end",
        "deleted", "createuser", "modifyuser", "modifydate", "school_code", "createdate", "image_link"
    ];
}
