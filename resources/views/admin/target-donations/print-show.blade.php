<!DOCTYPE html>
<html>
<head>
    <title>Target Donation Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.6;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .logo {
            max-width: 200px;
            height: auto;
        }
        h1 {
            color: #333;
            margin-bottom: 10px;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
            text-align: center;
        }
        .print-date {
            text-align: right;
            font-size: 12px;
            color: #666;
            margin-bottom: 20px;
        }
        .donation-info {
            margin-bottom: 30px;
        }
        .info-section {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .info-section h2 {
            margin-top: 0;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            color: #444;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .info-label {
            font-weight: bold;
            color: #555;
        }
        .info-value {
            text-align: right;
        }
        .target-amount {
            font-size: 24px;
            color: #2c7a2c;
            font-weight: bold;
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
    
    <h1>Target Donation Details</h1>
    <div style="text-align: center; margin-bottom: 20px;">
        <div>Period: <strong>{{ $targetDonation->period }}</strong></div>
    </div>
    
    <div class="donation-info">
        <div class="info-section">
            <h2>Period Information</h2>
            <div class="info-row">
                <span class="info-label">Period:</span>
                <span class="info-value">{{ $targetDonation->period }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Start Date:</span>
                <span class="info-value">{{ \Carbon\Carbon::parse($targetDonation->start_date)->format('F d, Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">End Date:</span>
                <span class="info-value">{{ \Carbon\Carbon::parse($targetDonation->end_date)->format('F d, Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Duration:</span>
                <span class="info-value">{{ \Carbon\Carbon::parse($targetDonation->end_date)->diffInDays(\Carbon\Carbon::parse($targetDonation->start_date)) }} days</span>
            </div>
        </div>
        
        <div class="info-section">
            <h2>Financial Information</h2>
            <div class="info-row">
                <span class="info-label">Target Amount:</span>
                <span class="info-value target-amount">${{ number_format($targetDonation->target_amount, 2) }}</span>
            </div>
        </div>
    </div>
    
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