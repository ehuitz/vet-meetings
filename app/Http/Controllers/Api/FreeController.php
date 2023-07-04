<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\FreeService;
use Illuminate\Http\Request;
use Carbon\Carbon;


class FreeController extends Controller
{
    /**
     * Get the available consultation times for veterinarians.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Services\FreeService  $freeService
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, FreeService $freeService)
    {
        $request->validate([
            'date' => 'required|date',
            'interval' => 'required|integer',
        ]);

        $date = $request->input('date');
        $intervalMinutes = $request->input('interval');

        $availableTimes = $freeService->generateIntervals($date, $intervalMinutes);

        return response()->json(['slots' => $availableTimes]);
    }
}
