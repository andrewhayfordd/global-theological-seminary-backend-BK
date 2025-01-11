<?php

namespace App\Http\Controllers;

use App\Http\Requests\InventoryDistCreateRequest;
use App\Http\Requests\InventoryDistDeleteRequest;
use App\Http\Requests\InventoryDistUpdateRequest;
use App\Repositories\InventoryDistRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InventoryDistControlller extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([]);
    }

    public function create(InventoryDistRepository $inventoryDistRepository, InventoryDistCreateRequest $request): JsonResponse
    {
        $inventoryDistRepository->create($request);
        return response()->json([
            'ok' => true,
            'msg' => 'Student inventory created',
        ]);
    }

    public function update(InventoryDistRepository $inventoryDistRepository, InventoryDistUpdateRequest $request): JsonResponse
    {
        $inventoryDistRepository->update($request);
        return response()->json([
            'msg' => 'Student inventory created',
            'ok' => true,
        ]);
    }

    public function delete(InventoryDistRepository $inventoryDistRepository, InventoryDistDeleteRequest $request): JsonResponse
    {
        $inventoryDistRepository->delete($request);
        return response()->json([
            'ok' => true,
            'msg' => 'Student inventory created',
        ]);
    }
}
