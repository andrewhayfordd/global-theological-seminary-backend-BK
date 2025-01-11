<?php

namespace App\Models;

use App\Casts\IdCast;
use App\Casts\PadIdCast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupplierContactPosition extends Model
{
    use HasFactory;

    protected $table = 'tblposition';
    protected $primaryKey = 'position_code';
    protected $keyType = 'string';
    protected $dateFormat = 'Y-m-d';
    public $incrementing = false;

    const CREATED_AT = 'createdate';
    const UPDATED_AT = 'modifydate';

    protected $casts = [
        'createdate' => 'datetime:Y-m-d',
        'modifydate' => 'datetime:Y-m-d',
        'transid' => IdCast::class,
        'position_code' => PadIdCast::class,
    ];


    protected $fillables = [
        'position_code',
        'position_desc',
    ];

    protected $attributes = [
        "deleted" => 0
    ];

}
