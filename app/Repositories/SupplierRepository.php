<?php

namespace App\Repositories;

use App\Http\Requests\SupplierCreateRequest;
use App\Http\Requests\SupplierDeleteRequest;
use App\Http\Requests\SupplierUpdateRequest;
use App\Http\Resources\SupplierResource;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Cache;

class SupplierRepository
{
    public function  __construct()
    {
    }

    public function createSupplier(SupplierCreateRequest $request): Supplier
    {
        return Supplier::create([
            'transid' => '',
            'supplier_code' => '',
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'createuser' => $request->createuser,
            'school_code' => $request->school_code,
        ]);
    }

    public function updateSupplier(SupplierUpdateRequest $request): bool
    {
        $supplier = $this->getWhereSupplier($request->transid)->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'modifyuser' => $request->createuser,
        ]);

        return $supplier;
    }

    public function deleteSupplier(SupplierDeleteRequest $request): bool
    {
        $supplier = $this->getWhereSupplier($request->transid)->update([
            "deleted" => 1,
            'modifyuser' => $request->createuser,
        ]);
        return $supplier;
    }

    public function getSupplier($id): Supplier
    {
        return Supplier::notDeleted()->where('transid', $id)->first();
    }

    public function getWhereSupplier($id)
    {
        return Supplier::notDeleted()->where('transid', $id);
    }

    public function getAllSupplier(): Collection
    {
        return Supplier::notDeleted()->withCount([
            'members' => function (Builder $query) {
                $query->where('deleted', 0);
            },
        ])->get();
}

    public function cachedAllSuppliers(): Collection
    {
        return Cache::remember('get-suppliers', now()->addSeconds(5), fn () => $this->getAllSupplier());
    }

    public function getApiAllSupplier(): ResourceCollection
    {
        return SupplierResource::collection($this->cachedAllSuppliers());
    }
}
