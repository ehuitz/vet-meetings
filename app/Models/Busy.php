<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;


class Busy extends Model
{
    use HasFactory;
    protected $guarded = [];


    /**
     * Get the Schedule that owns the Busy
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    /**
     * Get the scheduleVeterinarian for the Busy
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOneThrough
     */
    public function scheduleVeterinarian(): HasOneThrough
    {
        return $this->hasOneThrough(Veterinarian::class, Schedule::class);
    }
}
