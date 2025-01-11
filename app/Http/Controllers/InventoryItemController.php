<?php

namespace App\Http\Controllers;

use App\Http\Requests\InventoryItemCreateRequest;
use App\Http\Requests\InventoryItemDeleteRequest;
use App\Http\Requests\InventoryItemUpdateRequest;
use App\Repositories\InventoryItemRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InventoryItemController extends Controller
{

    public function index(InventoryItemRepository $inventoryItemRepository,): JsonResponse
    {
        return response()->json([
            'data' => $inventoryItemRepository->getApiAllItem()
        ]);
    }
    public function create(InventoryItemRepository $inventoryItemRepository, InventoryItemCreateRequest $request): JsonResponse
    {
        $inventoryItemRepository->create($request);
        return response()->json([
            'ok' => true,
            'msg' => "Inventory item created",
        ]);
    }

    public function update(InventoryItemRepository $inventoryItemRepository, InventoryItemUpdateRequest $request) : JsonResponse
    {
        $inventoryItemRepository->update($request);
        return response()->json([
            'ok' => true,
            'msg' => 'Inventory item updated'
        ]);
    }

    public function delete(InventoryItemRepository $inventoryItemRepository, InventoryItemDeleteRequest $request) : JsonResponse
    {
        $inventoryItemRepository->delete($request);
        return response()->json([
            'ok' => true,
            'msg' => 'Inventory item deleted',
        ]);
    }
}
