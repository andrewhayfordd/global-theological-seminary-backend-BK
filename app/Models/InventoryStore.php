<?php

namespace App\Models;

use App\Casts\IdCast;
use App\Scopes\DeletedScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InventoryStore extends Model
{
    use HasFactory;

    protected $table = 'tblinventory_store';
    protected $primarykey = 'transid';
    protected $dateFormat = 'Y-m-d';
    protected $keyType = "string";
    public $incrementing = false;

    const CREATED_AT = 'createdate';
    const UPDATED_AT = 'modifydate';

    protected $casts = [
        'createdate' => 'datetime:Y-m-d',
        'modifydate' => 'datetime:Y-m-d',
        'transid' => IdCast::class,
    ];

    protected $attributes = [
        "deleted" => 0,
    ];

    protected $fillable = [
        "school_code",
        "transid",
        "item_code",
        "item_quantity",
        'supply_date',
        "modifyuser",
        "createuser",
        'deleted',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new DeletedScope);
    }


    public function item(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class,"item_code","item_code");
    }
}
