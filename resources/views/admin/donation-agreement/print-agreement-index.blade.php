<!DOCTYPE html>
<html>
<head>
    <title>{{ $companyName }} - Donation Agreements Report</title>
    <style>
        @page {
            margin: 1cm;
            size: A4 landscape;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            min-width: 1200px;
            padding: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 3px solid #2d3748;
        }
        .logo {
            height: 60px;
            filter: grayscale(100%);
        }
        .title {
            font-size: 24px;
            font-weight: 700;
            color: #2d3748;
        }
        .report-info {
            margin-bottom: 1.5rem;
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            background: white;
        }
        th {
            background-color: #2d3748;
            color: white;
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
        }
        td {
            padding: 10px 15px;
            border-bottom: 1px solid #e2e8f0;
        }
        tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .numeric {
            text-align: right;
        }
        .footer {
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 2px solid #e2e8f0;
            text-align: center;
            font-size: 12px;
            color: #718096;
        }
        .print-button {
            background: #2d3748;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: opacity 0.3s;
            border: none;
            margin-bottom: 1rem;
        }
        .print-button:hover {
            opacity: 0.9;
        }
        @media print {
            .print-button { display: none; }
            .logo { filter: none; }
        }
        .logo { filter: none; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <div class="title">{{ $companyName }}</div>
            <div class="subtitle">Donation Agreements Report</div>
        </div>
        <div>
            <button onclick="window.print()" class="print-button">
                Print Report
            </button>
            <img src="{{ $logoUrl }}" alt="Company Logo">
        </div>
    </div>

    <div class="report-info">
        <div>Generated on: {{ $generatedDate }}</div>
        <div>Total Agreements: {{ $donationAgreements->count() }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Supporter</th>
                <th>Donation Type</th>
                <th class="numeric">Amount</th>
                <th>Recurring Interval</th>
                <th>Bank Form</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($donationAgreements as $agreement)
            <tr>
                <td>{{ $agreement->id }}</td>
                <td>{{ $agreement->supporter ? $agreement->supporter->name : 'N/A' }}</td>
                <td>{{ ucfirst($agreement->donation_type) }}</td>
                <td class="numeric">${{ number_format($agreement->donation_amount, 2) }}</td>
                <td>{{ $agreement->recurring_interval }}</td>
                <td>{{ $agreement->bankForm ? $agreement->bankForm->bank_name . ' - ' . $agreement->bankForm->form_name : 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        {{ $companyName }} © {{ now()->year }}. All rights reserved.
    </div>

   
</body>
</html>