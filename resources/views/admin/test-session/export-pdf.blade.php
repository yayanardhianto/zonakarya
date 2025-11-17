<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Sessions</title>
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
        
        .info-section {
            margin-bottom: 20px;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }
        
        .info-section h3 {
            margin: 0 0 10px 0;
            font-size: 16px;
            color: #333;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        
        .info-row:last-child {
            margin-bottom: 0;
        }
        
        .info-label {
            font-weight: bold;
            color: #555;
        }
        
        .info-value {
            color: #333;
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
        
        .status-in-progress {
            background-color: #007bff;
            color: white;
        }
        
        .status-completed {
            background-color: #28a745;
            color: white;
        }
        
        .status-expired {
            background-color: #dc3545;
            color: white;
        }
        
        .score-passed {
            color: #28a745;
            font-weight: bold;
        }
        
        .score-failed {
            color: #dc3545;
            font-weight: bold;
        }
        
        .progress-bar {
            width: 100%;
            height: 10px;
            background-color: #e9ecef;
            border-radius: 2px;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background-color: #007bff;
            transition: width 0.3s ease;
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
    width: 16%;
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
        
        .session-details {
            margin-top: 20px;
        }
        
        .session-card {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
            background-color: #fafafa;
        }
        
        .session-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .session-id {
            font-weight: bold;
            color: #333;
        }
        
        .session-status {
            font-size: 10px;
        }
        
        .session-info {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 8px;
            font-size: 8px;
        }
        
        .session-info .label {
            font-weight: bold;
            color: #555;
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
        
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Test Sessions</h1>
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

    @if($sessions->count() > 0)
        <div class="summary-stats">
            <div class="stat-item">
                <div class="stat-number">{{ $sessions->count() }}</div>
                <div class="stat-label">Total Sessions</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $sessions->where('status', 'completed')->count() }}</div>
                <div class="stat-label">Completed</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $sessions->where('status', 'in_progress')->count() }}</div>
                <div class="stat-label">In Progress</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $sessions->where('status', 'pending')->count() }}</div>
                <div class="stat-label">Pending</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $sessions->where('status', 'expired')->count() }}</div>
                <div class="stat-label">Expired</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $sessions->filter(function($s) { return $s->is_passed === true || (isset($s->multiple_choice_is_passed) && $s->multiple_choice_is_passed === true); })->count() }}</div>
                <div class="stat-label">Passed</div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 4%;">ID</th>
                    <th style="width: 12%;">Applicant/User</th>
                    <th style="width: 10%;">Package</th>
                    <th style="width: 8%;">Job</th>
                    <th style="width: 6%;">Status</th>
                    <th style="width: 6%;">Score</th>
                    <th style="width: 6%;">Progress</th>
                    <th style="width: 10%;">Started At</th>
                    <th style="width: 10%;">Completed At</th>
                    <th style="width: 8%;">Duration</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sessions as $session)
                    <tr>
                        <td style="text-align: center;">{{ $session->id }}</td>
                        <td>
                            @if($session->applicant)
                                <strong>{{ $session->applicant->name }}</strong><br>
                                <small>{{ $session->applicant->email }}</small>
                            @elseif($session->user)
                                <strong>{{ $session->user->name }}</strong><br>
                                <small>{{ $session->user->email }}</small>
                            @else
                                <span style="color: #999;">N/A</span>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $session->package->name }}</strong><br>
                            <small>{{ $session->package->category->name }}</small>
                        </td>
                        <td>
                            @if($session->jobVacancy)
                                {{ $session->jobVacancy->position }}
                            @elseif($session->application && $session->application->jobVacancy)
                                {{ $session->application->jobVacancy->position }}
                            @else
                                <span style="color: #999;">N/A</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <span class="status-badge status-{{ $session->status }}">
                                {{ ucfirst($session->status) }}
                            </span>
                        </td>
                        <td style="text-align: center;">
                            @if($session->score !== null)
                                <span class="{{ $session->is_passed ? 'score-passed' : 'score-failed' }}">
                                    {{ $session->score }}%
                                </span>
                                <br><small>{{ $session->is_passed ? 'Passed' : 'Failed' }}</small>
                            @elseif(isset($session->multiple_choice_score) && $session->multiple_choice_score !== null)
                                <span class="{{ $session->multiple_choice_is_passed ? 'score-passed' : 'score-failed' }}">
                                    {{ $session->multiple_choice_score }}%
                                </span>
                                <br><small>{{ $session->multiple_choice_is_passed ? 'Passed' : 'Failed' }} (MC Only)</small>
                            @else
                                <span style="color: #999;">N/A</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            @if($session->isInProgress())
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: {{ $session->progress_percentage }}%;"></div>
                                </div>
                                <small>{{ $session->progress_percentage }}%</small>
                            @else
                                <span style="color: #999;">N/A</span>
                            @endif
                        </td>
                        <td>
                            @if($session->started_at)
                                {{ $session->started_at->format('d M Y H:i') }}
                            @else
                                <span style="color: #999;">Not Started</span>
                            @endif
                        </td>
                        <td>
                            @if($session->completed_at)
                                {{ $session->completed_at->format('d M Y H:i') }}
                            @else
                                <span style="color: #999;">Not Completed</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            @if($session->started_at && $session->completed_at)
                                {{ $session->started_at->diffInMinutes($session->completed_at) }} min
                            @else
                                <span style="color: #999;">N/A</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="session-details">
            <h3>Session Details</h3>
            @foreach($sessions as $index => $session)
                @if($index > 0 && $index % 8 == 0)
                    <div class="page-break"></div>
                @endif
                <div class="session-card">
                    <div class="session-header">
                        <div class="session-id">Session #{{ $session->id }}</div>
                        <div class="session-status">
                            <span class="status-badge status-{{ $session->status }}">
                                {{ ucfirst($session->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="session-info">
                        <div>
                            <span class="label">User:</span>
                            @if($session->applicant)
                                {{ $session->applicant->name }}
                            @elseif($session->user)
                                {{ $session->user->name }}
                            @else
                                N/A
                            @endif
                        </div>
                        <div>
                            <span class="label">Package:</span>
                            {{ $session->package->name }}
                        </div>
                        <div>
                            <span class="label">Job:</span>
                            {{ $session->jobVacancy ? $session->jobVacancy->position : ($session->application && $session->application->jobVacancy ? $session->application->jobVacancy->position : 'N/A') }}
                        </div>
                        <div>
                            <span class="label">Score:</span>
                            @if($session->score !== null)
                                <span class="{{ $session->is_passed ? 'score-passed' : 'score-failed' }}">
                                    {{ $session->score }}%
                                </span>
                            @elseif(isset($session->multiple_choice_score) && $session->multiple_choice_score !== null)
                                <span class="{{ $session->multiple_choice_is_passed ? 'score-passed' : 'score-failed' }}">
                                    {{ $session->multiple_choice_score }}% (MC Only)
                                </span>
                            @else
                                N/A
                            @endif
                        </div>
                        <div>
                            <span class="label">Progress:</span>
                            @if($session->isInProgress())
                                {{ $session->progress_percentage }}%
                            @else
                                N/A
                            @endif
                        </div>
                        <div>
                            <span class="label">Duration:</span>
                            @if($session->started_at && $session->completed_at)
                                {{ $session->started_at->diffInMinutes($session->completed_at) }}m
                            @else
                                N/A
                            @endif
                        </div>
                        <div>
                            <span class="label">Started:</span>
                            {{ $session->started_at ? $session->started_at->format('d M H:i') : 'Not Started' }}
                        </div>
                        <div>
                            <span class="label">Completed:</span>
                            {{ $session->completed_at ? $session->completed_at->format('d M H:i') : 'Not Completed' }}
                        </div>
                        <div>
                            <span class="label">Email:</span>
                            @if($session->applicant)
                                {{ $session->applicant->email }}
                            @elseif($session->user)
                                {{ $session->user->email }}
                            @else
                                N/A
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="no-data">
            <h3>No Sessions Found</h3>
            <p>No test sessions match the current filter criteria.</p>
        </div>
    @endif

    <div class="footer">
        <p>This report was generated automatically by the Zona Karya Nusantara</p>
        <p>© {{ date('Y') }} - All rights reserved</p>
    </div>
</body>
</html>
