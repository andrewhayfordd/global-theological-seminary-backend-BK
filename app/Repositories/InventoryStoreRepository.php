<?php

namespace App\Repositories;

use App\Http\Resources\InventoryStoreResource;
use App\Interfaces\Crudable;
use App\Models\InventoryStore;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\ResourceCollection;

class InventoryStoreRepository implements Crudable
{
    public function create(Request $request): Model
    {
        return InventoryStore::create([
            "school_code" => $request->school_code,
            "transid" => '',
            "item_code" => $request->item_code,
            "item_quantity" => $request->item_quantity,
            'supply_date' => $request->supply_date,
        ]);
    }
    public function update(Request $request, $value = null): bool
    {
        $store = $this->getBuilder($request->transid)->update([
            "item_code" => $request->item_code,
            "item_quantity" => $request->item_quantity,
            'supply_date' => $request->supply_date,
            'modifyuser' => $request->createuser,
        ]);
        return $store;
    }

    public function delete(?Request $request, $value = null): bool
    {
        $store = $this->getBuilder($request->transid)->update([
            'deleted' => 1,
            'modifyuser' => $request->createuser,
        ]);
        return $store;
    }

    public function getBuilder($code): Builder
    {
        return InventoryStore::query()->where('transid', $code);
    }

    public function getAll(): Collection
    {
        return InventoryStore::query()->where(request()->query(''))->get();
    }

    public function getApiAll(): ResourceCollection
    {
        return InventoryStoreResource::collection($this->getAll());
    }
}
