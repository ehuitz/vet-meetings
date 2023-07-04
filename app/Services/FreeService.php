<?php

namespace App\Services;

use App\Models\Schedule;
use App\Models\Busy;
use App\Models\Veterinarian;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Support\Collection;


class FreeService
{
    public function generateIntervals(string $date, int $intervalMinutes): Collection
    {
        $startDate = Carbon::parse($date)->startOfDay();
        $endDate = $startDate->copy()->endOfDay();

        $possibleSlots = $this->generatePossibleSlots($startDate, $endDate, $intervalMinutes);
        $busySlots = $this->generateBusySlots($startDate, $endDate, $intervalMinutes);
        $availableSlots = collect();

        foreach ($possibleSlots as $slot) {
            $slotStart = $slot['start'];
            $slotEnd = $slot['end'];
            $veterinarianName = $slot['name'];

            if (!$this->isSlotBusy($slotStart, $slotEnd, $veterinarianName, $busySlots)) {
                $availableSlots->push([
                    'start' => $slotStart,
                    'end' => $slotEnd,
                    'veterinarian_name' => $veterinarianName,
                ]);
            }
        }

        return $availableSlots->map(function ($slot) {
            return $slot['start'] . ' - ' . $slot['end'] . ' ' . $slot['veterinarian_name'];
        });
    }

    private function generatePossibleSlots(Carbon $startDate, Carbon $endDate, int $intervalMinutes): Collection
    {
        $veterinarians = Veterinarian::orderBy('name')->get();

        $slots = collect();

        $interval = CarbonInterval::minutes($intervalMinutes);
        $currentDateTime = $startDate->copy();

        while ($currentDateTime->lt($endDate)) {
            foreach ($veterinarians as $veterinarian) {
                $schedules = $veterinarian->schedules()->where('start_date', '<=', $currentDateTime->toDateString())
                    ->where('end_date', '>=', $currentDateTime->toDateString())->get();

                foreach ($schedules as $schedule) {
                    $scheduleStartDateTime = $schedule->startDateTime();
                    $scheduleEndDateTime = $schedule->endDateTime();

                    // that the slots is between the vets working hours and does not go over his schedule
                    if ($currentDateTime->between($scheduleStartDateTime, $scheduleEndDateTime) && $currentDateTime->copy()->add($interval) <= $scheduleEndDateTime ) {
                        $slots->push([
                            'start' => $currentDateTime->copy(),
                            'end' => $currentDateTime->copy()->add($interval),
                            'name' => $veterinarian->name,
                        ]);
                    }
                }
            }

            $currentDateTime->add($interval);
        }
        return $slots;
    }

    private function generateBusySlots(Carbon $startDate, Carbon $endDate, int $intervalMinutes): Collection
    {
        $busySlots = collect();

        $veterinarians = Veterinarian::orderBy('name')->get();

        foreach ($veterinarians as $veterinarian) {
            $busies = $veterinarian->busies()->whereDate('start_date', '>=', $startDate)
                ->whereDate('end_date', '<=', $endDate)
                ->get();

            foreach ($busies as $busy) {
                $startDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $busy->schedule->start_date . ' ' . $busy->start_time);
                $endDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $busy->schedule->end_date . ' ' . $busy->end_time);

                $interval = CarbonInterval::minutes($intervalMinutes);
                $currentDateTime = $startDateTime->copy();

                while ($currentDateTime->lt($endDateTime)) {
                    $busySlots->push([
                        'start' => $currentDateTime->copy(),
                        'end' => $currentDateTime->copy()->add($interval),
                        'name' => $busy->schedule->veterinarian->name
                    ]);

                    $currentDateTime->add($interval);
                }
            }
        }
        return $busySlots;
    }

    private function isSlotBusy(Carbon $start, Carbon $end, string $veterinarianName, Collection $busySlots): bool
    {
        return $busySlots->contains(function ($busySlot) use ($start, $end, $veterinarianName) {
            $busyStartDateTime = $busySlot['start'];
            $busyEndDateTime = $busySlot['end'];
            $vet = $busySlot['name'];

            //compare the possible slot to the busy slots, can add other conditions here to confirm that its not a busy slots for some weird time slots.

            return $start->equalTo( $busyStartDateTime)
                && $end->equalTo($busyEndDateTime)
                && $veterinarianName === $vet;
        });
    }
}
