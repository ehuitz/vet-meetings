<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
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
            'veterinarian_id' => $this->veterinarian_id,
            'employee_id' => $this->veterinarian->employee_id,
            'name' => $this->veterinarian->name,
            'start_date' => date("Y-M-d", strtotime($this->start_date)),
            'start_time' => date("H:i:s", strtotime($this->start_time)),
            'end_date' => date("Y-M-d", strtotime($this->end_date)),
            'end_time' =>date("H:i:s", strtotime($this->end_time)),
        ];
    }
}
