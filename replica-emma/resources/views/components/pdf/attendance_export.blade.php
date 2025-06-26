<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendance Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            font-size: 18px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 5px 7px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .footer {
            margin-top: 20px;
            font-size: 10px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Attendance Report</h2>
        <p>Generated on {{ now()->format('d M Y, H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Employee Code</th>
                <th>Full Name</th>
                <th>Date</th>
                <th>Clock In</th>
                <th>Clock Out</th>
                <th>Clock In Status</th>
                <th>Clock Out Status</th>
                <th>Work Duration (Min)</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($attendances as $i => $a)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $a->employee->employee_code ?? '-' }}</td>
                    <td>{{ $a->employee->full_name ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($a->date)->format('d M Y') }}</td>
                    <td>{{ $a->clock_in ?? '-' }}</td>
                    <td>{{ $a->clock_out ?? '-' }}</td>
                    <td>{{ ucfirst($a->clock_in_status) ?? '-' }}</td>
                    <td>{{ ucfirst($a->clock_out_status) ?? '-' }}</td>
                    <td>{{ $a->work_duration ?? 0 }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center;">No attendance data available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Printed by system at {{ now()->format('d-m-Y H:i') }}
    </div>
</body>
</html>
