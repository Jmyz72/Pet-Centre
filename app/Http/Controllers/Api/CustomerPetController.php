<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerPetRequest;
use App\Http\Resources\CustomerPetResource;
use App\Models\CustomerPet;

class CustomerPetController extends Controller
{
    /**
     * GET /api/customers/{customer}/pets
     */
    public function index(CustomerPetRequest $request, int $customer)
    {
        $data = $request->validated();

        $q = CustomerPet::query()
            ->where('user_id', $customer);

        if (!empty($data['limit'])) {
            $q->limit($data['limit']);
        }

        $pets = $q->orderBy('name')
                  ->select(['id','name','pet_type_id','pet_breed_id','size_id','sex','photo_path'])
                  ->get();

        return response()->json([
            'ok'   => true,
            'data' => CustomerPetResource::collection($pets),
        ]);
    }

    // Placeholders for other RESTful methods
    public function store() {}
    public function show() {}
    public function update() {}
    public function destroy() {}
}
