<?php

namespace App\Models;

use App\Casts\IdCast;
use App\Casts\PadIdCast;
use App\Scopes\DeletedScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InventoryItem extends Model
{
    use HasFactory;
    protected $table = 'tblinventory_item';
    protected $primarykey = 'item_code';
    protected $dateFormat = 'Y-m-d';
    protected $keyType = "string";
    public $incrementing = false;

    const CREATED_AT = 'createdate';
    const UPDATED_AT = 'modifydate';

    protected $casts = [
        'createdate' => 'datetime:Y-m-d',
        'modifydate' => 'datetime:Y-m-d',
        'transid' => IdCast::class,
        'item_code' => PadIdCast::class
    ];

    protected $attributes = [
        "deleted" => 0
    ];

    protected $fillable = [
        "school_code",
        "transid",
        "item_code",
        "item_desc",
        "modifyuser",
        "createuser",
        'deleted',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new DeletedScope);
    }
}
