<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    const CREATED_AT = "createdate";
    const UPDATED_AT = "modifydate";

    const TYPE_STUDENT = "STU";     // Represents a student usertype
    const TYPE_STAFF   = "STA";     // Represents a staff usertype
    const TYPE_ADMIN   = "ADM";     // Represents an admin usertype

    const USERTYPES = [
        self::TYPE_ADMIN,
        self::TYPE_STAFF,
        self::TYPE_STUDENT,
    ];

    protected $table = "tbluser";
    public $incrementing = false;
    protected $primaryKey = "id";
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id', "school_code", "userid", "phone",'email', 'password',"usertype", "deleted",
        "modifydate", "modifyuser", "createdate","createuser",
        
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getRouteKeyName()
    {
        return "id";
    }

    public function school()
    {
        return $this->belongsTo("App\Models\School", "school_code", "school_code");
    }

    public function acyear()
    {
        return $this->belongsTo("App\Models\AcademicDetails", "school_code", "school_code");
    }
   
}
