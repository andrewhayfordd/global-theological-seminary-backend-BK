<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupplierMemberCreateRequest;
use App\Http\Requests\SupplierMemberDeleteRequest;
use App\Http\Requests\SupplierMemberUpdateRequest;
use App\Repositories\SupplierMemberRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SupplierMemberController extends Controller
{
    public function index(SupplierMemberRepository $supplierMemberRepository, Request $request): JsonResponse
    {
        return response()->json([
            'data' =>  $supplierMemberRepository->getApiAllSupplierMember()
        ]);
    }

    public function create(SupplierMemberRepository $supplierMemberRepository,SupplierMemberCreateRequest $request) :JsonResponse
    {
        $supplierMemberRepository->createSupplierMember($request);
        return response()->json([
            'ok' => true,
            'msg'=> "Supplier member details created!",
            'data' => [],
        ]);
    }

    public function update(SupplierMemberRepository $supplierMemberRepository, SupplierMemberUpdateRequest $request) : JsonResponse
    {
        $supplierMemberRepository->updateSupplierMember($request);
        return response()->json([
            'ok' => true,
            'msg'=> "Supplier member details updated!",
            'data' => []
        ]);
        
    }

    public function delete(SupplierMemberRepository $supplierMemberRepository, SupplierMemberDeleteRequest $request): JsonResponse
    {
        $supplierMemberRepository->deleteSupplierMember($request);
        return response()->json([
            'ok' => true,
            'msg'=> "Supplier member details deleted!",
            'data' => []
        ]);
    }
}
