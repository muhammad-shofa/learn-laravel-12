<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pay Slip</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 14px;
            line-height: 1.5;
            color: #333;
            padding: 20px;
        }
        h2 {
            text-align: center;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        td, th {
            padding: 8px;
            border: 1px solid #999;
        }
        .text-right {
            text-align: right;
        }
        .text-left {
            text-align: left;
        }
        .summary {
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <h2>Employee Payslip</h2>
    <p><strong>Name:</strong> {{ $salary->employee->full_name }}</p>
    @php
    $monthName = \Carbon\Carbon::createFromDate(null, $salary->month, 1)->format('F');
    @endphp
    <p><strong>Month:</strong> {{ $monthName }} {{ $salary->year }}</p>

    <table>
        <tr>
            <th>Description</th>
            <th class="text-right">Amount (Rp)</th>
        </tr>
        <tr>
            <td>Basic Salary</td>
            <td class="text-right">{{ number_format($salary->salarySetting->default_salary, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Hour Deduction</td>
            <td class="text-right">-{{ number_format($salary->hour_deduction, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Absent Deduction</td>
            <td class="text-right">-{{ number_format($salary->absent_deduction, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Bonus</td>
            <td class="text-right">{{ number_format($salary->bonus, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Deduction</td>
            <td class="text-right">-{{ number_format($salary->deduction, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Total Amount Paid</th>
            <th class="text-right">{{ number_format($salary->total_salary, 0, ',', '.') }}</th>
        </tr>
    </table>

    <div class="summary">
        <p><strong>Payment Date:</strong> {{ \Carbon\Carbon::parse($salary->payment_date)->format('d M Y') }}</p>
        <p><em>This slip is automatically generated and does not require a signature.</em></p>
    </div>
</body>
</html>
