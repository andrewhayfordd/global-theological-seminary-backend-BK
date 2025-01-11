<?php

namespace App\Repositories;

use App\Http\Resources\InventoryBranchResource;
use App\Interfaces\Crudable;
use App\Models\InventoryBranch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\ResourceCollection;

class InventoryBranchRepository implements Crudable
{
    public function create(Request $request): InventoryBranch
    {
        return InventoryBranch::create([
            'transid' => '',
            ...$request->safe(),
            'createuser' => $request->createuser,
        ]);
    }

    public function update(Request $request, $value = null): bool
    {
        $branch =  $this->getBuilder($request->transid)->update([
            ...$request->safe()->except(['transid', 'school_code']),
            'modifyuser' => $request->createuser,
        ]);
        return $branch;
    }

    public function delete(?Request $request, $value = null): bool
    {
        $branch =  $this->getBuilder($request->transid)->update([
            'deleted' => 1,
            'modifyuser' => $request->createuser,
        ]);
        return $branch;
    }

    public function getBuilder($code): Builder
    {
        return InventoryBranch::query()->where('transid', $code);
    }

    public function getAll(): Collection
    {
        return InventoryBranch::with(['item'])->get();
    }

    public function getApiAll(): ResourceCollection
    {
        return  InventoryBranchResource::collection($this->getAll());
    }
}
