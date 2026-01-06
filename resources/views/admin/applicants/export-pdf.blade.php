<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicants</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.2;
            margin: 0;
            padding: 10px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        
        .header .subtitle {
            margin-top: 10px;
            font-size: 14px;
            color: #666;
        }
        
        .filter-info {
            background-color: #e7f3ff;
            border-left: 4px solid #007bff;
            padding: 10px;
            margin-bottom: 20px;
        }
        
        .filter-info h4 {
            margin: 0 0 10px 0;
            color: #007bff;
            font-size: 14px;
        }
        
        .filter-info ul {
            margin: 0;
            padding-left: 20px;
        }
        
        .filter-info li {
            margin-bottom: 3px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 4px;
            text-align: left;
            vertical-align: top;
        }
        
        th {
            background-color: #4472C4;
            color: white;
            font-weight: bold;
            text-align: center;
            font-size: 9px;
        }
        
        td {
            font-size: 8px;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .status-badge {
            padding: 1px 4px;
            border-radius: 2px;
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-pending {
            background-color: #ffc107;
            color: #212529;
        }
        
        .status-sent {
            background-color: #17a2b8;
            color: white;
        }
        
        .status-check {
            background-color: #007bff;
            color: white;
        }
        
        .status-short_call {
            background-color: #28a745;
            color: white;
        }
        
        .status-group_interview {
            background-color: #17a2b8;
            color: white;
        }
        
        .status-test_psychology {
            background-color: #6c757d;
            color: white;
        }
        
        .status-ojt {
            background-color: #343a40;
            color: white;
        }
        
        .status-final_interview {
            background-color: #007bff;
            color: white;
        }
        
        .status-sent_offering_letter {
            background-color: #28a745;
            color: white;
        }
        
        .status-rejected {
            background-color: #dc3545;
            color: white;
        }
        
        .status-rejected_by_applicant {
            background-color: #dc3545;
            color: white;
        }
        
        .summary-stats {
            display: table;
            width: 100%;
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .stat-item {
            display: table-cell;
            text-align: center;
            width: 12.5%;
            vertical-align: middle;
        }

        .stat-item:not(:last-child) {
            border-right: 1px solid #ddd;
        }
        
        .stat-number {
            font-size: 14px;
            font-weight: bold;
            color: #333;
        }
        
        .stat-label {
            font-size: 8px;
            color: #666;
            margin-top: 3px;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
        }
        
        .no-data h3 {
            margin: 0 0 10px 0;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Applicants Report</h1>
        <div class="subtitle">Generated on: {{ $exportDate }}</div>
    </div>

    @if(!empty($filterInfo))
        <div class="filter-info">
            <h4>Applied Filters:</h4>
            <ul>
                @foreach($filterInfo as $filter)
                    <li>{{ $filter }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($applications->count() > 0)
        <div class="summary-stats">
            <div class="stat-item">
                <div class="stat-number">{{ $applications->count() }}</div>
                <div class="stat-label">Total Applicants</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $applications->where('status', 'pending')->count() }}</div>
                <div class="stat-label">Pending</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $applications->where('status', 'sent')->count() }}</div>
                <div class="stat-label">Test Screening</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $applications->where('status', 'check')->count() }}</div>
                <div class="stat-label">Check</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $applications->where('status', 'short_call')->count() }}</div>
                <div class="stat-label">Short Call</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $applications->where('status', 'group_interview')->count() }}</div>
                <div class="stat-label">Group Interview</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $applications->where('status', 'final_interview')->count() }}</div>
                <div class="stat-label">Final Interview</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $applications->where('status', 'rejected')->count() + $applications->where('status', 'rejected_by_applicant')->count() }}</div>
                <div class="stat-label">Rejected</div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 3%;">ID</th>
                    <th style="width: 12%;">Name</th>
                    <th style="width: 12%;">Email</th>
                    <th style="width: 8%;">Phone</th>
                    <th style="width: 8%;">WhatsApp</th>
                    <th style="width: 12%;">Position Applied</th>
                    <th style="width: 10%;">Company</th>
                    <th style="width: 8%;">Status</th>
                    <th style="width: 10%;">Applied Date</th>
                    <th style="width: 7%;">Provider</th>
                    <th style="width: 10%;">Interviewer</th>
                    <th style="width: 12%;">Notes</th>
                </tr>
            </thead>
            <tbody>
                @foreach($applications as $application)
                    <tr>
                        <td style="text-align: center;">{{ $application->id }}</td>
                        <td>
                            <strong>{{ $application->user->name ?? $application->applicant->name ?? 'N/A' }}</strong>
                        </td>
                        <td>{{ $application->user->email ?? $application->applicant->email ?? 'N/A' }}</td>
                        <td>{{ $application->applicant->phone ?? 'N/A' }}</td>
                        <td>{{ $application->applicant->whatsapp ?? 'N/A' }}</td>
                        <td>
                            {{ $application->jobVacancy->position ?? 'N/A' }}
                            @if($application->jobVacancy->location)
                                <br><small style="color: #666;">📍 {{ $application->jobVacancy->location }}</small>
                            @endif
                        </td>
                        <td>{{ $application->jobVacancy->company_name ?? 'N/A' }}</td>
                        <td style="text-align: center;">
                            <span class="status-badge status-{{ $application->status }}">
                                {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                            </span>
                        </td>
                        <td>{{ $application->created_at->format('d M Y H:i') }}</td>
                        <td style="text-align: center;">
                            @if($application->applicant->provider)
                                {{ ucfirst($application->applicant->provider) }}
                            @else
                                <span style="color: #999;">N/A</span>
                            @endif
                        </td>
                        <td>
                            {{ $application->interviewer->name ?? 'N/A' }}
                        </td>
                        <td>
                            @if($application->notes)
                                {{ \Illuminate\Support\Str::limit($application->notes, 200) }}
                            @else
                                <span style="color: #999;">-</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            <h3>No Applicants Found</h3>
            <p>No applicants match the current filter criteria.</p>
        </div>
    @endif

    <div class="footer">
        <p>This report was generated automatically by the Zona Karya Nusantara</p>
        <p>© {{ date('Y') }} - All rights reserved</p>
    </div>
</body>
</html>

