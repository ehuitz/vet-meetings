<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVeterinarianRequest;
use App\Http\Requests\UpdateVeterinarianRequest;
use App\Models\Veterinarian;
use App\Http\Resources\VeterinarianResource;
use Illuminate\Http\Request;


class VeterinarianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orderColumn = request('order_column');
        if (!in_array($orderColumn, ['id', 'employee_id'])) {
            $orderColumn = 'employee_id';
        }

        $orderDirection = request('order_direction', 'desc');
        if (!in_array($orderDirection, ['asc', 'desc'])) {
            $orderDirection = 'desc';
        }

        $query = Veterinarian::query();

        if (request('search_id')) {
            $query->where('employee_id', request('search_id'))->orWhere('name', 'LIKE','%'.request('search_id').'%');
        }

        $query->orderBy($orderColumn, $orderDirection);

        return VeterinarianResource::collection($query->paginate(10));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVeterinarianRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Veterinarian $veterinarian)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Veterinarian $veterinarian)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVeterinarianRequest $request, Veterinarian $veterinarian)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Veterinarian $veterinarian)
    {
        //
    }
}
