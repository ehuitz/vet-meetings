<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSyncRequest;
use App\Http\Requests\UpdateSyncRequest;
use App\Models\Sync;
use App\Http\Resources\SyncResource;
use Illuminate\Support\Facades\Storage;

class SyncController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orderColumn = request('order_column');
        if (!in_array($orderColumn, ['id', 'created_at'])) {
            $orderColumn = 'created_at';
        }

        $orderDirection = request('order_direction', 'desc');
        if (!in_array($orderDirection, ['asc', 'desc'])) {
            $orderDirection = 'desc';
        }

        $query = Sync::query();

        $query->orderBy($orderColumn, $orderDirection);

        return SyncResource::collection($query->paginate(10));
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
    public function store(StoreSyncRequest $request)
    {
        $file = $request->file('file');
        $file_name = time().'_'. $file->getClientOriginalName();
        $origin = $request->input('origin');

        $path = $file->store('public/files');
        $fileUrl = Storage::url($path);

        $sync = Sync::create([
            'origin' => $origin,
            'path' => $path,
            'name' => $file_name,
            'status' => 'active'
        ]);

        $query = Sync::query();
        return SyncResource::collection($query->paginate(10));
    }

    /**
     * Display the specified resource.
     */
    public function show(Sync $sync)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sync $sync)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSyncRequest $request, Sync $sync)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sync $sync)
    {
        //
    }
}
