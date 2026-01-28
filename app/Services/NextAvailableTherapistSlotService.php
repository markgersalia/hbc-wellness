<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Therapist;
use App\Models\Bed;
use Carbon\Carbon;

class NextAvailableTherapistSlotService
{
    public static function find(
        int $therapistId,
        Carbon $startDate,
        // int $branchId,
        int $searchDays = 30
    ): ?array {

        $therapist = Therapist::active()->find($therapistId);

        if (! $therapist) {
            return null;
        }

        $date = $startDate->copy()->startOfDay();

        for ($day = 0; $day < $searchDays; $day++) {

            $slots = Booking::availableTimeslots($date->toDateString());

            foreach ($slots as $slot) {
                [$start, $end] = explode(' - ', $slot);

                $slotStart = Carbon::parse("{$date->toDateString()} {$start}");
                $slotEnd   = Carbon::parse("{$date->toDateString()} {$end}");

                // 1️⃣ Therapist availability
                if (
                    $therapist->isOnLeave($slotStart, $slotEnd) ||
                    ! $therapist->isAvailable(
                        $date->toDateString(),
                        $slotStart,
                        $slotEnd
                    )
                ) {
                    continue;
                }

                // 2️⃣ Bed availability
                $bed = Bed::available()
                    // ->where('branch_id', $branchId)
                    ->get()
                    ->first(fn ($b) =>
                        $b->isAvailable(
                            $date->toDateString(),
                            $slotStart,
                            $slotEnd
                        )
                    );

                if (! $bed) {
                    continue;
                }

                // 🎯 FOUND earliest valid slot
                return [
                    'date'       => $date->toDateString(),
                    'start_time' => $slotStart,
                    'end_time'   => $slotEnd,
                    'bed_id'     => $bed->id,
                ];
            }

            $date->addDay();
        }

        return null;
    }
}
