<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $companyName }} - Supporter Report</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 1cm; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        .logo { height: 60px; }
        .title { font-size: 24px; font-weight: bold; color: #1a365d; }
        .subtitle { font-size: 16px; color: #4a5568; }
        .main-content { display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; }
        .profile-photo { text-align: right; margin-top: 1rem; }
        .photo { max-width: 150px; height: auto; border: 2px solid #e2e8f0; border-radius: 0.5rem; }
        .section { margin-bottom: 1.5rem; }
        .section-title { font-size: 18px; font-weight: bold; color: #2d3748; border-bottom: 2px solid #e2e8f0; padding-bottom: 0.5rem; margin-bottom: 1rem; }
        .info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; }
        .info-item { margin-bottom: 0.75rem; }
        .info-label { font-weight: 500; color: #4a5568; font-size: 14px; }
        .info-value { color: #2d3748; margin-top: 0.25rem; font-size: 14px; }
        .footer { margin-top: 2rem; text-align: center; font-size: 12px; color: #718096; }
        @media print {
            body { margin: 0.5cm; }
            .section { page-break-inside: avoid; }
        }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <div class="title">{{ $companyName }}</div>
            <div class="subtitle">Supporter Details Report</div>
        </div>
        <img src="{{ $logoUrl }}" alt="Company Logo" class="logo">
    </div>

    <div class="main-content">
        <div>
            <!-- Personal Information Section -->
            <div class="section">
                <div class="section-title">Personal Information</div>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Full Name</div>
                        <div class="info-value">{{ $supporter->name }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Age</div>
                        <div class="info-value">{{ $supporter->age }} years</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Gender</div>
                        <div class="info-value">{{ $supporter->gender }}</div>
                    </div>
                </div>
            </div>

            <!-- Contact Information Section -->
            <div class="section">
                <div class="section-title">Contact Information</div>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Email</div>
                        <div class="info-value">{{ $supporter->email }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Phone</div>
                        <div class="info-value">{{ $supporter->phone_number }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Address</div>
                        <div class="info-value">{{ $supporter->address }}</div>
                    </div>
                </div>
            </div>

            <!-- Support Details Section -->
            <div class="section">
                <div class="section-title">Support Details</div>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Support Type</div>
                        <div class="info-value">{{ $supporter->support_type }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Contribution Amount</div>
                        <div class="info-value">${{ $supporter->contribution_amount }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Commitment Duration</div>
                        <div class="info-value">{{ $supporter->commit_duration }} months</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Start Date</div>
                        <div class="info-value">{{ $startDate }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Photo Section -->
        <div class="profile-photo">
            @if($photoUrl)
                <img src="{{ $photoUrl }}" alt="Profile Photo" class="photo">
            @else
                No photo available
            @endif
        </div>
    </div>

    <div class="footer">
        Generated on {{ $generatedDate }} | {{ $companyName }} © All rights reserved
    </div>
</body>
</html>