<!-- report.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-semibold mb-4">Employee Attendance Report with sandwitch</h1>
        <table class="table-auto w-full border border-gray-300">
            <thead>
            <tr>
                <th class="px-4 py-2">Employee Name</th>
                <th class="px-4 py-2">Present</th>
                <th class="px-4 py-2">Absent</th>
                <th class="px-4 py-2">Paid Leave</th>
                <th class="px-4 py-2">LWP</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($consolidatedReport as $employeeId => $reportData)
                <tr>
                    <td class="border px-4 py-2">{{ $reportData['employee_name'] }}</td>
                    <td class="border px-4 py-2">{{ $reportData['present_days'] }}</td>
                    <td class="border px-4 py-2">{{ $reportData['absent_days'] }}</td>
                    <td class="border px-4 py-2">{{ $reportData['paid_leave_days'] }}</td>
                    <td class="border px-4 py-2">{{ $reportData['lwp_days'] }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
