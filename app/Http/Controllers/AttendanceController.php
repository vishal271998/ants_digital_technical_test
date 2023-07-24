<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function reportWithSandwitch()
    {
        // Get all employees' attendance records for the specified date range
        $startDate = '2023-07-20'; // Replace with your desired start date
        $endDate = '2023-07-31'; // Replace with your desired end date
        $attendances = Attendance::where('user_id', '1')->whereBetween('date', [$startDate, $endDate])->get();

        // Process attendance data as per the Sandwich system cases
        foreach ($attendances as $key => $attendance) {
            // Check if the current day is a Friday
            $dayOfWeek = date('N', strtotime($attendance->date));
            if ($dayOfWeek == 5) { // 5 corresponds to Friday
                // Check if the employee is on leave on Friday and absent on Monday
                $mondayDate = date('Y-m-d', strtotime($attendance->date . ' + 3 days'));
                $mondayAttendance = $attendances->where('date', $mondayDate)->first();

                if (!$mondayAttendance) {
                    // Mark 4 days of absence for the employee
                    $attendance->status = 'Absent';
                    $attendance->save();
                } elseif ($mondayAttendance->status === 'LWP') {
                    // If Friday was a paid leave, set Saturday/Sunday/Monday as LWP
                    $attendances->where('date', '>=', $attendance->date)
                        ->where('date', '<=', $mondayDate)
                        ->each(function ($item) {
                            $item->status = 'LWP';
                            $item->save();
                        });
                }
            }
        }

        // Create an array to hold the consolidated report data
        $consolidatedReport = [];

        // Calculate the total number of days for each type (present, absent, paid leave, LWP) for each employee
        foreach ($attendances as $attendance) {
            $employeeId = $attendance->employee_id;
            if (!isset($consolidatedReport[$employeeId])) {
                $consolidatedReport[$employeeId] = [
                    'employee_name' => $attendance->user->name, // Replace 'name' with your actual employee name column
                    'present_days' => 0,
                    'absent_days' => 0,
                    'paid_leave_days' => 0,
                    'lwp_days' => 0,
                ];
            }

            switch ($attendance->status) {
                case 'Present':
                    $consolidatedReport[$employeeId]['present_days']++;
                    break;
                case 'Absent':
                    $consolidatedReport[$employeeId]['absent_days']++;
                    break;
                case 'Paid':
                    $consolidatedReport[$employeeId]['paid_leave_days']++;
                    break;
                case 'LWP':
                    $consolidatedReport[$employeeId]['lwp_days']++;
                    break;
            }
        }

        // Load the view and pass the attendance data and consolidated report
        return view('sandwitch_report', ['consolidatedReport' => $consolidatedReport]);
    }

    public function reportWithoutSandwitch()
    {
        // Get all employees' attendance records for the specified date range
        $startDate = '2023-07-20'; // Replace with your desired start date
        $endDate = '2023-07-31'; // Replace with your desired end date
        $attendances = Attendance::where('user_id', '2')->whereBetween('date', [$startDate, $endDate])->get();

        // Create an array to hold the consolidated report data
        $consolidatedReport = [];

        // Calculate the total number of days for each type (present, absent, paid leave, LWP) for each employee
        $daysThreshold = 10; // Number of consecutive days for absence to be counted
        $consecutiveWeekendsMissed = 0; // Counter to track consecutive weekends missed

        foreach ($attendances as $attendance) {
            $employeeId = $attendance->employee_id;
            if (!isset($consolidatedReport[$employeeId])) {
                $consolidatedReport[$employeeId] = [
                    'employee_name' => $attendance->user->name, // Replace 'name' with your actual employee name column
                    'present_days' => 0,
                    'absent_days' => 0,
                    'paid_leave_days' => 0,
                    'lwp_days' => 0,
                ];
            }

            $attendanceDate = strtotime($attendance->date);
            $nextWorkingDay = date('Y-m-d', strtotime($attendance->date . ' + 1 weekday'));

            if ($attendance->status === 'Present') {
                $consolidatedReport[$employeeId]['present_days']++;
                $consecutiveWeekendsMissed = 0; // Reset the counter for consecutive weekends missed
            } else {
                // Check if the absence is more than 10 consecutive days
                $isAbsentMoreThan10Days = false;
                $absenceCounter = 0;

                while ($absenceCounter < $daysThreshold) {
                    if (!$this->isWorkingDay($nextWorkingDay)) {
                        $absenceCounter++;
                    } else {
                        break;
                    }
                    $nextWorkingDay = date('Y-m-d', strtotime($nextWorkingDay . ' + 1 day'));
                }

                if ($absenceCounter >= $daysThreshold) {
                    $isAbsentMoreThan10Days = true;
                }

                // Handle marking LWP for missing consecutive weekends
                if ($isAbsentMoreThan10Days && !$this->isWorkingDay($attendance->date)) {
                    $consecutiveWeekendsMissed++;

                    if ($consecutiveWeekendsMissed >= 2) {
                        $consolidatedReport[$employeeId]['lwp_days']++;
                        $consecutiveWeekendsMissed = 0; // Reset the counter for consecutive weekends missed
                    }
                } else {
                    $consecutiveWeekendsMissed = 0; // Reset the counter for consecutive weekends missed
                }

                if ($isAbsentMoreThan10Days) {
                    $consolidatedReport[$employeeId]['absent_days']++;
                } else {
                    $consolidatedReport[$employeeId]['paid_leave_days']++;
                }
            }
        }

        // Load the view and pass the attendance data and consolidated report
        return view('without_sandwitch_report', ['consolidatedReport' => $consolidatedReport]);
    }

    // Helper function to check if a given date is a working day (Monday to Friday)
    private function isWorkingDay($date)
    {
        $dayOfWeek = date('N', strtotime($date));
        return ($dayOfWeek >= 1 && $dayOfWeek <= 5); // 1 to 5 correspond to Monday to Friday
    }

}
