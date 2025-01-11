<?php

namespace App\Repositories;

use App\Http\Requests\SupplierMemberCreateRequest;
use App\Http\Resources\SupplierMemberResource;
use App\Models\SupplierMember;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SupplierMemberRepository 
{
    public function  __construct()
    {
    }

    public function createSupplierMember(SupplierMemberCreateRequest $request): SupplierMember
    {
        return SupplierMember::create([
            "transid" => "",
            "supplier_code" => $request->supplier_code,
            "fname" => $request->fname,
            "lname" => $request->lname,
            'phone' => $request->phone,
            "position_code" => $request->position_code,
            'createuser' => $request->createuser,
        ]);
    }

    public function updateSupplierMember(Request $request): bool
    {
        $supplierMember = $this->getWhereSupplierMember($request->transid)->update([
            "supplier_code" => $request->supplier_code,
            "fname" => $request->fname,
            "lname" => $request->lname,
            'phone' => $request->phone,
            "position_code" => $request->position_code,
            'modifyuser' => $request->createuser,
        ]);
        return $supplierMember;
    }

    public function deleteSupplierMember(Request $request): bool
    {
        $supplierMember = $this->getWhereSupplierMember($request->transid)->update([
            'deleted' =>  1,
            'modifyuser' => $request->createuser,
        ]);
        return $supplierMember;
    }

    public function getWhereSupplierMember($id)
    {
        return SupplierMember::notDeleted()->where('transid', $id);
    }

    public function getSupplierMember($id): SupplierMember
    {
        return SupplierMember::notDeleted()->where('transid', $id)->first();
    }

    public function getAllSupplierMember(): Collection
    {
        return SupplierMember::notDeleted()->with('supplier','position')->get();
    }

    public function getApiAllSupplierMember() : ResourceCollection
    {
        return SupplierMemberResource::collection($this->getAllSupplierMember());
    }
}
