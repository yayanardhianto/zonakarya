<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Test Packages</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #333;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .filter-info {
            background-color: #f8f9fa;
            padding: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #007bff;
        }
        .filter-info h3 {
            margin: 0 0 10px 0;
            color: #333;
            font-size: 16px;
        }
        .filter-info ul {
            margin: 0;
            padding-left: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #4472C4;
            color: white;
            font-weight: bold;
            text-align: center;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .status-active {
            color: #28a745;
            font-weight: bold;
        }
        .status-inactive {
            color: #dc3545;
            font-weight: bold;
        }
        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-primary {
            background-color: #007bff;
            color: white;
        }
        .badge-secondary {
            background-color: #6c757d;
            color: white;
        }
        .badge-success {
            background-color: #28a745;
            color: white;
        }
        .badge-warning {
            background-color: #ffc107;
            color: #212529;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .package-details {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .package-details h4 {
            margin: 0 0 10px 0;
            color: #333;
            font-size: 14px;
        }
        .package-details .row {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 5px;
        }
        .package-details .col {
            flex: 1;
            min-width: 150px;
        }
        .package-details .label {
            font-weight: bold;
            color: #555;
        }
        .summary-items {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .summary-item {
            flex: 1;
            min-width: 150px;
        }
        .summary-item p {
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Test Packages</h1>
        <p>Generated on: {{ $exportDate }}</p>
        @if(!empty($filterInfo))
            <p>Filtered Results</p>
        @endif
    </div>

    @if(!empty($filterInfo))
        <div class="filter-info">
            <h3>Applied Filters:</h3>
            <ul>
                @foreach($filterInfo as $filter)
                    <li>{{ $filter }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="summary">
        <h3>Summary</h3>
        <div class="summary-items">
            <div class="summary-item">
                <p><strong>Total Packages:</strong> {{ $packages->count() }}</p>
            </div>

            <div class="summary-item">  
                <p><strong>Active Packages:</strong> {{ $packages->where('is_active', true)->count() }}</p>
            </div>

            <div class="summary-item">
                <p><strong>Inactive Packages:</strong> {{ $packages->where('is_active', false)->count() }}</p>
            </div>

            <div class="summary-item">
                <p><strong>Applicant Flow Packages:</strong> {{ $packages->where('is_applicant_flow', true)->count() }}</p>
            </div>

            <div class="summary-item">
                <p><strong>General Test Packages:</strong> {{ $packages->where('is_applicant_flow', false)->count() }}</p>
            </div>
        </div>
    </div>

    @if($packages->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Package Name</th>
                    <th>Category</th>
                    <th>Duration</th>
                    <th>Questions</th>
                    <th>Passing Score</th>
                    <th>Sessions</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                @foreach($packages as $package)
                    <tr>
                        <td>{{ $package->id }}</td>
                        <td><strong>{{ $package->name }}</strong></td>
                        <td>
                            <span class="badge badge-secondary">{{ $package->category->name }}</span>
                        </td>
                        <td>{{ $package->duration_minutes }} min</td>
                        <td>{{ $package->total_questions }}</td>
                        <td>{{ $package->passing_score }}%</td>
                        <td>
                            <span class="badge badge-primary">{{ $package->sessions_count }}</span>
                        </td>
                        <td>
                            @if($package->is_applicant_flow)
                                <span class="badge badge-warning">Applicant Flow</span>
                                @if($package->is_screening_test)
                                    <br><small>Screening Test</small>
                                @else
                                    <br><small>Order: {{ $package->applicant_flow_order }}</small>
                                @endif
                            @else
                                <span class="badge badge-secondary">General Test</span>
                            @endif
                        </td>
                        <td>
                            @if($package->is_active)
                                <span class="status-active">Active</span>
                            @else
                                <span class="status-inactive">Inactive</span>
                            @endif
                        </td>
                        <td>{{ $package->created_at->format('d M Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 30px;">
            <h3>Package Details</h3>
            @foreach($packages as $package)
                <div class="package-details">
                    <h4>{{ $package->name }} (ID: {{ $package->id }})</h4>
                    <div class="row">
                        <div class="col">
                            <span class="label">Category:</span> {{ $package->category->name }}
                        </div>
                        <div class="col">
                            <span class="label">Duration:</span> {{ $package->duration_minutes }} minutes
                        </div>
                        <div class="col">
                            <span class="label">Questions:</span> {{ $package->total_questions }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <span class="label">Passing Score:</span> {{ $package->passing_score }}%
                        </div>
                        <div class="col">
                            <span class="label">Sessions:</span> {{ $package->sessions_count }}
                        </div>
                        <div class="col">
                            <span class="label">Status:</span> 
                            @if($package->is_active)
                                <span class="status-active">Active</span>
                            @else
                                <span class="status-inactive">Inactive</span>
                            @endif
                        </div>
                    </div>
                    @if($package->description)
                        <div class="row">
                            <div class="col">
                                <span class="label">Description:</span> {{ $package->description }}
                            </div>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col">
                            <span class="label">Show Score to User:</span> {{ $package->show_score_to_user ? 'Yes' : 'No' }}
                        </div>
                        <div class="col">
                            <span class="label">Randomize Questions:</span> {{ $package->randomize_questions ? 'Yes' : 'No' }}
                        </div>
                        <div class="col">
                            <span class="label">Created:</span> {{ $package->created_at->format('d M Y H:i:s') }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div style="text-align: center; padding: 40px; color: #666;">
            <h3>No packages found</h3>
            <p>No test packages match the current filter criteria.</p>
        </div>
    @endif

    <div class="footer">
        <p>This report was generated automatically by the Zona Karya Nusantara</p>
        <p>© {{ date('Y') }} - All rights reserved</p>
    </div>
</body>
</html>
