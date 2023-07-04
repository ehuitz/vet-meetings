<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;


class Veterinarian extends Model
{
    use HasFactory;
    protected $guarded = [];

    /**
     * Get all of the schedules for the Veterinarian
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * Get all of the busies for the Veterinarian
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function busies(): HasManyThrough
    {
        return $this->hasManyThrough(Busy::class, Schedule::class);
    }

}
