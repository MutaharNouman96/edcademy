<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\EducatorSessionSchedule;

class EducatorSessionScheduleSeeder extends Seeder
{
    /**
     * Add a default weekly schedule for each educator.
     * Mon–Fri 9:00–17:00 by default.
     */
    public function run(): void
    {
        $educators = User::where('role', 'educator')->pluck('id');

        if ($educators->isEmpty()) {
            $this->command->warn('No educators found. Skip EducatorSessionScheduleSeeder.');
            return;
        }

        $defaultSlots = [
            ['day_of_week' => 1, 'start_time' => '09:00', 'end_time' => '17:00'], // Monday
            ['day_of_week' => 2, 'start_time' => '09:00', 'end_time' => '17:00'], // Tuesday
            ['day_of_week' => 3, 'start_time' => '09:00', 'end_time' => '17:00'], // Wednesday
            ['day_of_week' => 4, 'start_time' => '09:00', 'end_time' => '17:00'], // Thursday
            ['day_of_week' => 5, 'start_time' => '09:00', 'end_time' => '17:00'], // Friday
        ];

        $count = 0;
        foreach ($educators as $educatorId) {
            foreach ($defaultSlots as $slot) {
                EducatorSessionSchedule::firstOrCreate(
                    [
                        'educator_id' => $educatorId,
                        'day_of_week' => $slot['day_of_week'],
                        'start_time' => $slot['start_time'],
                        'end_time'   => $slot['end_time'],
                    ],
                    [
                        'educator_id' => $educatorId,
                        'day_of_week' => $slot['day_of_week'],
                        'start_time' => $slot['start_time'],
                        'end_time'   => $slot['end_time'],
                    ]
                );
                $count++;
            }
        }

        $this->command->info("EducatorSessionScheduleSeeder: added/kept {$count} schedule slot(s) for " . $educators->count() . " educator(s).");
    }
}
