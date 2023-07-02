<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBusyRequest;
use App\Http\Requests\UpdateBusyRequest;
use App\Models\Busy;
use App\Http\Resources\BusyResource;


class BusyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orderColumn = request('order_column', 'schedule_id');
        if (!in_array($orderColumn, ['id', 'schedule_id', 'start_time', 'end_time'])) {
            $orderColumn = 'schedule_id';
        }

        $orderDirection = request('order_direction', 'desc');
        if (!in_array($orderDirection, ['asc', 'desc'])) {
            $orderDirection = 'desc';
        }

        $query = Busy::query();

        if (request('search_id')) {
            $query->whereHas('schedule', function ($q) {
                $q->where('veterinarian_id', request('search_id'));
            });
        }

        $query->orderBy($orderColumn, $orderDirection);

        return BusyResource::collection($query->paginate(10));
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
    public function store(StoreBusyRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Busy $busy)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Busy $busy)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBusyRequest $request, Busy $busy)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Busy $busy)
    {
        //
    }
}
