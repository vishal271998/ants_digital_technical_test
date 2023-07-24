<?php

namespace Database\Seeders;

use App\Models\Attendance;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $attendances = [
            [1, '2023-07-20', 'Thursday', 'Present'],
            [1, '2023-07-21', 'Friday', 'Leave'],
            [1, '2023-07-22', 'Saturday', 'Week-Off'],
            [1, '2023-07-23', 'Sunday', 'Week-Off'],
            [1, '2023-07-24', 'Monday', 'Absent'],
            [1, '2023-07-25', 'Tuesday', 'Present'],

            [2, '2023-07-19', 'Wednesday', 'Leave'],
            [2, '2023-07-20', 'Thursday', 'Leave'],
            [2, '2023-07-21', 'Friday', 'Leave'],
            [2, '2023-07-22', 'Saturday', 'Leave'],
            [2, '2023-07-23', 'Sunday', 'Leave'],
            [2, '2023-07-24', 'Monday', 'Leave'],
            [2, '2023-07-25', 'Tuesday', 'Leave'],
            [2, '2023-07-26', 'Wednesday', 'Leave'],
            [2, '2023-07-27', 'Thursday', 'Leave'],
            [2, '2023-07-28', 'Friday', 'Leave'],
            [2, '2023-07-29', 'Saturday', 'Week-Off'],
            [2, '2023-07-30', 'Sunday', 'Week-Off'],
        ];

        foreach ($attendances as $attendance) {
            Attendance::create([
                'user_id' => $attendance[0],
                'date' => $attendance[1],
                'day' => $attendance[2],
                'status' => $attendance[3],
            ]);
        }

    }
}
