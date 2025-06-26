<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Time Off Report</title>
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
        <h2>Time Off Report</h2>
        <p>Generated on {{ now()->format('d M Y, H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Employee Code</th>
                <th>Full Name</th>
                <th>Request Date</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Reason</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($time_offs as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item->employee->employee_code ?? '-' }}</td>
                    <td>{{ $item->employee->full_name ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->request_date)->format('d M Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->start_date)->format('d M Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->end_date)->format('d M Y') }}</td>
                    <td>{{ $item->reason }}</td>
                    <td>{{ ucfirst($item->status) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">No time off data available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Printed by system at {{ now()->format('d-m-Y H:i') }}
    </div>
</body>
</html>
