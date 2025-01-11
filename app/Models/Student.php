<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = "tblstudent";
    public $incrementing = false;
    protected $primaryKey = "student_no";
    const CREATED_AT = 'createdate';
    const UPDATED_AT = 'modifydate';

    protected $fillable = [
        "transid", "school_code","branch_code", "admyear", "admsemester", "admdate", "student_no",
        "gender", "fname", "mname", "lname", "dob", "pob","hometown", "nationality", "religion", 
        "email", "phone", "postal_add", "residential_gps", "prog", "current_level", "level_admitted",
        "hostel_code", "disability", "disability_details", "picture", "completed","deleted", "batch",
        "createdate","createuser", "modifydate", "modifyuser","session","education_level",
         
        "marital_status", "church_name", "phy_challenge", "emerg_cont_name", "emerg_cont_number",
        "prog_reason",
                    "eng_lang_grade",
                    "eng_lang_year",
                    "math_grade",
                    "math_year",
                    "science_grade",
                    "science_year",
                    "elective1_grade",
                    "elective1_year",
                    "elective2_grade",
                    "elective2_year",
                    "sch_attended_name",
                    "certificate",
                    "date_awarded",
                    "religious_affiliation",
                    "employer_name",
                    "refree_name",
                    "refree_phone",
                    "refree_occ",
                    "refree_address",
    ];

    public function parents()
    {
        return $this->belongsToMany("App\Guardian", "tblparent_student", "student_no", "parent_id");
    }

    public function school()
    {
        return $this->belongsTo("App\School", "school_code");
    }

    public function payments()
    {
        return $this->belongsToMany('App\Payment', 'student_no','student_no');
    }

    public function classRooms()
    {
        return $this->belongsTo('App\ClassRoom', 'class_code','current_class');
    }

    public function setFNameAttribute($value)
    {
        $this->attributes["fname"] = strtoupper($value);
    }

    public function setMNameAttribute($value)
    {
        $this->attributes["mname"] = strtoupper($value);
    }

    public function setLNameAttribute($value)
    {
        $this->attributes["lname"] = strtoupper($value);
    }
}
