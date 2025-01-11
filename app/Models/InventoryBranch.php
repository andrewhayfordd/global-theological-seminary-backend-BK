<?php

namespace App\Models;

use App\Casts\IdCast;
use App\Scopes\DeletedScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InventoryBranch extends Model
{
    use HasFactory;
    protected $table = 'tblinventory_branch';
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
        // 'item_code' => PadIdCast::class
    ];

    protected $attributes = [
        "deleted" => 0
    ];

    protected $fillable = [
        "transid",
        "item_code",
        "school_code",
        "branch_code",
        "item_quantity",
        "supply_date",
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

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class,"branch_code","branch_code");
    }
}
