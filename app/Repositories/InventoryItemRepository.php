<?php

namespace App\Repositories;

use App\Http\Controllers\InventoryItemResource;
use App\Http\Requests\InventoryItemCreateRequest;
use App\Http\Resources\InventoryItemResource as ResourcesInventoryItemResource;
use App\Interfaces\Crudable;
use App\Models\InventoryItem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Resources\Json\ResourceCollection;

class InventoryItemRepository implements Crudable
{
    public function create(Request $request): InventoryItem
    {
        return InventoryItem::create([
            'transid' => '',
            'school_code' => $request->school_code,
            'item_code' => 'INV-ITM',
            'item_desc' => $request->item_desc,
            'createuser' => $request->createuser,
        ]);
    }


    public function update(Request $request, $value = null): bool
    {
        $item = $this->getItemBuilder($request->item_code)->update([
            "item_desc" => $request->item_desc,
            'modifyuser' => $request->createuser,
        ]);
        return $item;
    }

    public function delete(?Request $request, $value = null): bool
    {
        $item = $this->getItemBuilder($request->item_code)->update([
            'modifyuser' => $request->createuser,
            'deleted' => 1,
        ]);
        return false;
    }

    public function getItemBuilder($code) : Builder
    {
        return InventoryItem::query()->where('item_code', $code);
    }


    public function getItem($code) : InventoryItem
    {
        return InventoryItem::query()->where('item_code', $code)->first();
    }

    public function getAllItem() : Collection
    {
        return InventoryItem::query()->where(request()->query(''))->get();
    }

    public function getApiAllItem() : ResourceCollection
    {
        return ResourcesInventoryItemResource::collection($this->getAllItem());
    }


}
