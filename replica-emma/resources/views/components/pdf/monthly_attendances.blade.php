<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Kehadiran Bulan {{ $month }}/{{ $year }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #333;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table, th, td {
            border: 1px solid #444;
        }
        th, td {
            padding: 6px 10px;
            text-align: left;
        }
        .status {
            text-transform: capitalize;
        }
        .footer {
            text-align: center;
            font-size: 10px;
            color: #999;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <h2>Monthly Attendances Employee Report <br>Month {{ $month }} / {{ $year }}</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Employee Name</th>
                <th>Date</th>
                <th>Clock In</th>
                <th>Clock Out</th>
                <th>Status Clock In</th>
                <th>Status Clock Out</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($attendances as $index => $a)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $a->employee->full_name }}</td>
                    <td>{{ \Carbon\Carbon::parse($a->date)->format('d-m-Y') }}</td>
                    <td>{{ $a->clock_in ?? '-' }}</td>
                    <td>{{ $a->clock_out ?? '-' }}</td>
                    <td class="status">
                        @php
                            $status = $a->clock_in_status;
                            $inClass = 'secondary';
                            if ($status === 'ontime') $inClass = 'success';
                            elseif ($status === 'late' || $status === 'absent') $inClass = 'danger';
                            elseif ($status === 'leave') $inClass = 'warning';
                        @endphp
                        <span style="color: {{ $inClass }}">{{ $status }}</span>
                    </td>
                    <td class="status">
                        @php
                            $status = $a->clock_out_status;
                            $outClass = 'secondary';
                            if ($status === 'ontime') $outClass = 'success';
                            elseif ($status === 'early') $outClass = 'warning';
                            elseif ($status === 'late' || $status === 'no_clock_out') $outClass = 'danger';
                        @endphp
                        <span style="color: {{ $outClass }}">{{ $status }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">No attendance data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Printed in {{ now()->format('d-m-Y H:i') }}
    </div>

</body>
</html>
