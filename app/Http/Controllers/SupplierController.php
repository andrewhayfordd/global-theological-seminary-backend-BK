<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Repositories\SupplierRepository;
use App\Http\Requests\SupplierCreateRequest;
use App\Http\Requests\SupplierDeleteRequest;
use App\Http\Requests\SupplierUpdateRequest;
use App\Models\SupplierContactPosition;
use Illuminate\Contracts\View\View;

class SupplierController extends Controller
{
    public function showPage(SupplierRepository  $supplierRepository) : View
    {
        return view("modules.supplier.index",[
            "suppliers" => $supplierRepository->getAllSupplier(),
            "positions" => SupplierContactPosition::all(),
        ]);
    }
    public function index(SupplierRepository $supplierRepository, Request $request) : JsonResponse
    {
        return response()->json([
            'data' => $supplierRepository->getApiAllSupplier($request)
        ]);
    }

    public function create(SupplierRepository $supplierRepository, SupplierCreateRequest $request): JsonResponse
    {
        $supplierRepository->createSupplier($request);
        return response()->json([
            'ok' => true,
            'msg' =>  "Creating supplier successful",
            'data' => [],
        ]);
    }

    public function update(SupplierRepository $supplierRepository,SupplierUpdateRequest $request): JsonResponse
    {
       
        $supplierRepository->updateSupplier($request);
        return response()->json([
            'ok' => true,
            'msg' =>  "Updating supplier details successful",
            'data' => [],
        ]);
    }

    public function delete(SupplierRepository $supplierRepository, SupplierDeleteRequest $request) : JsonResponse
    {
        $supplierRepository->deleteSupplier($request);
        return response()->json([
            'ok' => true,
            'msg' =>  "Deleting supplier successful",
            'data' => [],
        ]);
    }
}
