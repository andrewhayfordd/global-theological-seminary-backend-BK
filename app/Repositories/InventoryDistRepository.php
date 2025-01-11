<?php

namespace App\Repositories;

use App\Http\Resources\InventoryDistResource;
use App\Interfaces\Crudable;
use App\Models\InventoryDist;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\ResourceCollection;

class InventoryDistRepository implements Crudable
{
    public function create(Request $request): InventoryDist
    {
        return InventoryDist::create([
            'transid' =>'',
            ...$request->safe(),
            'createuser' => $request->createuser,
        ]);
    }

    public function update(Request $request, $value = null): bool
    {
        $dist = $this->getBuilder($request->transid)->update([
            ...$request->safe()->except(['transid']),
            'modifyuser' => $request->creatuser,
        ]);
        return $dist;
    }

    public function delete(?Request $request, $value = null): bool
    {
        $dist = $this->getBuilder($request->transid)->update([
            'deleted' => 1,
            'modifyuser' => $request->creatuser,
        ]);
        return $dist;
    }

    public function getBuilder($code) : Builder
    {
        return InventoryDist::query()->where('transid',$code);
    }

    public function getAll(): Collection
    {
        return InventoryDist::query()->with(['branch','item'])->get();
    }

    public function getApiAll() : ResourceCollection
    {
        return InventoryDistResource::collection($this->getAll());
    }

    

}