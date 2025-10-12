@extends('installer::app')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <p>Minimum Requirements</p>
            <div>
                <a class="btn btn-outline-primary" href="{{ route('setup.verify') }}">&laquo; Back</a>
                <a class="btn btn-outline-primary @if (!session()->has('requirements-complete')) disabled @endif"
                    href="{{ route('setup.database') }}">Next &raquo;</a>
            </div>
        </div>
        <div class="card-body">
            <ul class="list-group">
                @foreach ($checks as $key => $check)
                    <li class="list-group-item d-flex justify-content-between align-items-left">
                        <div>
                            @if (array_key_exists($key, $failedChecks))
                                <svg class="text-danger" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" viewBox="0 0 16 16">
                                    <path
                                        d="M8 1a7 7 0 1 0 0 14A7 7 0 0 0 8 1zm0 12.93A5.93 5.93 0 1 1 8 2.07a5.93 5.93 0 0 1 0 11.86zM7.002 4a1 1 0 0 1 2 0v4a1 1 0 0 1-2 0V4zm.93 6.412a1.5 1.5 0 1 1 2.12 2.12 1.5 1.5 0 0 1-2.12-2.12z" />
                                </svg>
                            @else
                                <svg class="text-success" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" viewBox="0 0 16 16">
                                    <path
                                        d="M16 2a2 2 0 0 1-3.293 1.293L6.707 9.293a1 1 0 0 1-1.414 0L1.293 5.293A2 2 0 1 1 2.707 3.707L6 7l6.293-6.293A2 2 0 0 1 16 2z" />
                                </svg>
                            @endif
                            <span>{{ __('installer::setup.' . $key) }}</span>
                        </div>
                        @if (array_key_exists($key, $failedChecks))
                            <span class="badge bg-danger rounded-pill">{{ $failedChecks[$key]['message'] }}
                                @if (isset($failedChecks[$key]['url']))
                                    <a class="text-warning" href="{{ $failedChecks[$key]['url'] }}" target="_blank"
                                        rel="noopener noreferrer">(!)</a>
                                @endif
                            </span>
                        @endif
                    </li>
                @endforeach
            </ul>
            <div class="d-flex justify-content-end align-items-center mt-3">
                @if ($success)
                    <a class="btn btn-primary" href="{{ route('setup.database') }}">
                        Next
                    </a>
                @else
                    <span class="text-danger text-small fw-bold me-2">Enable all extension then click reload
                        button</span>
                    <a class="btn btn-success" href="{{ route('setup.database') }}">
                        Reload <i class="fa fa-sync"></i>
                    </a>
                @endif
            </div>
        </div>
        <div class="card-footer text-center">
            <p>For script support, contact us at <a href="https://websolutionus.com/page/support" target="_blank"
                    rel="noopener noreferrer">@websolutionus</a>. We're here to help. Thank you!</p>
        </div>
    </div>
@endsection
