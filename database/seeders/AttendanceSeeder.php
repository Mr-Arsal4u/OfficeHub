<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Attendance;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $userId = 1;

        for ($day = 1; $day <= 30; $day++) {

            $date = Carbon::create(2025, 4, $day);


            $status = $this->getRandomStatus();


            if ($status === 'present') {
                $checkInTime = Carbon::createFromFormat('H:i', '09:00');
                $checkOutTime = Carbon::createFromFormat('H:i', '17:00');
            } else {

                $checkInTime = null;
                $checkOutTime = null;
            }

            Attendance::create([
                'employee_id' => $userId,
                'date' => $date->format('Y-m-d'),
                'status' => $status,
                'check_in_time' => $checkInTime,
                'check_out_time' => $checkOutTime,
            ]);
        }
    }
    private function getRandomStatus()
    {
        $statuses = ['present', 'absent', 'leave'];
        return $statuses[array_rand($statuses)];
    }
}
