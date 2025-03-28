<!DOCTYPE html>
<html>
<head>
    <title>Target Donations List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.6;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .print-date {
            text-align: right;
            font-size: 12px;
            color: #666;
            margin-bottom: 20px;
        }
        .logo {
            max-width: 200px;
            height: auto;
            margin-bottom: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ asset('images/logo.png') }}" alt="Company Logo" class="logo">
        <div class="print-date">
            Printed on: {{ date('F d, Y h:i A') }}
        </div>
    </div>
    
    <h1>Target Donations List</h1>
    
    <table>
        <thead>
            <tr>
                <th>Period</th>
                <th>Target Amount</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Duration (Days)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($targetDonations as $donation)
                <tr>
                    <td>{{ $donation->period }}</td>
                    <td>${{ number_format($donation->target_amount, 2) }}</td>
                    <td>{{ \Carbon\Carbon::parse($donation->start_date)->format('M d, Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($donation->end_date)->format('M d, Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($donation->end_date)->diffInDays(\Carbon\Carbon::parse($donation->start_date)) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <script>
        window.onload = function() {
            window.print();
            window.onafterprint = function() {
                window.close();
            };
        }
    </script>
</body>
</html>