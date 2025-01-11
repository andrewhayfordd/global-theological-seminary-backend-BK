<?php

namespace App\Models;

use App\Casts\IdCast;
use App\Casts\SupplierIdCast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'tblsupplier';
    protected $primarykey = 'supplier_code';
    protected $dateFormat = 'Y-m-d';
    protected $keyType = "string";
    public $incrementing = false;

    const CREATED_AT = 'createdate';
    const UPDATED_AT = 'modifydate';

    protected $casts = [
        'createdate' => 'datetime:Y-m-d',
        'modifydate' => 'datetime:Y-m-d',
        'transid' => IdCast::class,
        'supplier_code' => SupplierIdCast::class
    ];

    protected $attributes = [
        "deleted" => 0
    ];

    protected $fillable = [
        "school_code",
        "transid",
        "supplier_code",
        "name",
        "phone",
        "email",
        "address",
        "modifyuser",
        "createuser",
        'deleted',
    ];

    public function members(): HasMany
    {
        return $this->hasMany(SupplierMember::class, 'supplier_code', 'supplier_code');
    }

    public function scopeNotDeleted($query)
    {
        return $query->where('deleted', 0);
    }
}
