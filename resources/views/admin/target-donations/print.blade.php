<!DOCTYPE html>
<html>
<head>
    <title>Target Donations</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .print-button {
            display: none;
        }
        @media print {
            .print-button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <h1>Target Donations</h1>
    <table>
        <thead>
            <tr>
                <th>Period</th>
                <th>Target Amount</th>
                <th>Start Date</th>
                <th>End Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($targetDonations as $donation)
                <tr>
                    <td>{{ $donation->period }}</td>
                    <td>${{ number_format($donation->target_amount, 2) }}</td>
                    <td>{{ \Carbon\Carbon::parse($donation->start_date)->format('M d, Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($donation->end_date)->format('M d, Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <button class="print-button" onclick="window.print()">Print</button>
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>