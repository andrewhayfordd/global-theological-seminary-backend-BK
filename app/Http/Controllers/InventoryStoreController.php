<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Repositories\InventoryStoreRepository;
use App\Http\Requests\InventoryStoreCreateRequest;
use App\Http\Requests\InventoryStoreDeleteRequest;
use App\Http\Requests\InventoryStoreUpdateRequest;

class InventoryStoreController extends Controller
{
    public function index(InventoryStoreRepository $inventoryStoreRepository): JsonResponse
    {
        return response()->json([
            'data' => $inventoryStoreRepository->getApiAll()
        ]);
    }

    public function create(InventoryStoreRepository $inventoryStoreRepository, InventoryStoreCreateRequest $request): JsonResponse
    {
        $inventoryStoreRepository->create($request);
        return response()->json([
            'ok' => true,
            'msg' => 'Inventory recording success'
        ]);
    }

    public function update(InventoryStoreRepository $inventoryStoreRepository, InventoryStoreUpdateRequest $request): JsonResponse
    {
        $inventoryStoreRepository->update($request);
        return response()->json([
            'ok' => true,
            'msg' => 'Inventory updating success'
        ]);
    }

    public function delete(InventoryStoreRepository $inventoryStoreRepository, InventoryStoreDeleteRequest $request): JsonResponse
    {
        $inventoryStoreRepository->delete($request);
        return response()->json([
            'ok' => true,
            'msg' => 'Inventory deleting success'
        ]);
    }
}
