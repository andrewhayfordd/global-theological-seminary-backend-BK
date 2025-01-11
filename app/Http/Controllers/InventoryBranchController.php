<?php

namespace App\Http\Controllers;

use App\Http\Requests\InventoryBranchCreateRequest;
use App\Http\Requests\InventoryBranchDeleteRequest;
use App\Http\Requests\InventoryBranchUpdateRequest;
use App\Repositories\InventoryBranchRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InventoryBranchController extends Controller
{
    public function index(InventoryBranchRepository $inventoryBranchRepository) : JsonResponse
    {
        return response()->json([
            'data' => $inventoryBranchRepository->getApiAll()
        ]);
    }

    public function create(InventoryBranchRepository $inventoryBranchRepository, InventoryBranchCreateRequest $request): JsonResponse
    {
        $inventoryBranchRepository->create($request);
        return response()->json([
            'ok' => true,
            'msg' => 'Branch inventory created',
        ]);
    }

    public function update(InventoryBranchRepository $inventoryBranchRepository, InventoryBranchUpdateRequest $request) : JsonResponse
    {
        $inventoryBranchRepository->update($request);
       return response()->json([
            'ok' => true,
            'msg' => 'Branch inventory updated',
        ]);
    }

    public function delete(InventoryBranchRepository $inventoryBranchRepository, InventoryBranchDeleteRequest $request) : JsonResponse
    {
        $inventoryBranchRepository->delete($request);
        return response()->json([
            'ok' => true,
            'msg' => 'Branch inventory deleted',
        ]);
    }


}
