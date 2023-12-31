<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;


class Schedule extends Model
{
    use HasFactory;
    protected $guarded = [];

    /**
     * Get the Veterinarian that owns the Schedule
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function veterinarian(): BelongsTo
    {
        return $this->belongsTo(Veterinarian::class);
    }

    public function startDateTime(): Carbon
    {
        return Carbon::parse("{$this->start_date} {$this->start_time}");
    }

    public function endDateTime(): Carbon
    {
        return Carbon::parse("{$this->end_date} {$this->end_time}");
    }

}
