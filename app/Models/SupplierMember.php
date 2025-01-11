<?php

namespace App\Models;

use App\Casts\IdCast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SupplierMember extends Model
{
    use HasFactory;

    protected $table = 'tblsupplier_member';
    protected $primaryKey = 'transid';
    protected $keyType = 'string';
    protected $dateFormat = "Y-m-d";
    public   $incrementing = false;

    const CREATED_AT = 'createdate';
    const UPDATED_AT = 'modifydate';

    protected $fillable = [
        'transid',
        'supplier_code',
        'fname',
        'lname',
        'phone',
        'position_code',
        "createuser",
        "modifyuser",
    ];
    
    protected $attributes = [
        "deleted" => 0
    ];

    protected $casts = [
        'createdate' => 'datetime:Y-m-d',
        'modifydate' => 'datetime:Y-m-d',
        'transid' => IdCast::class,
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class,'supplier_code','supplier_code');
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(SupplierContactPosition::class,'position_code','position_code');
    }

    public function scopeNotDeleted($query)
    {
        return $query->where('deleted', 0);
    }

    public function fullName()
    {
        return $this->fname." ".$this->lname;
    }
}
