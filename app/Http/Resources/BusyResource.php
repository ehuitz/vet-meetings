<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BusyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'schedule_id' => $this->schedule_id,
            'veterinarian_id' => $this->schedule->veterinarian->employee_id,
            'name' => $this->schedule->veterinarian->name,
            'start_date' => date("Y-M-d", strtotime($this->schedule->start_date)),
            'end_date' => date("Y-M-d", strtotime($this->schedule->end_date)),
            'start_time' => date("H:i:s", strtotime($this->start_time)),
            'end_time' =>date("H:i:s", strtotime($this->end_time)),
        ];
    }
}
