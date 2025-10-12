@extends('admin.master_layout')

@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>{{ __('Branch Details') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></div>
                <div class="breadcrumb-item"><a href="{{ route('admin.branch.index') }}">{{ __('Branch') }}</a></div>
                <div class="breadcrumb-item">{{ __('Details') }}</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ $branch->name }}</h4>
                            <div class="card-header-action">
                                @if(checkAdminHasPermission('branch.edit'))
                                    <a href="{{ route('admin.branch.edit', $branch) }}" class="btn btn-primary">
                                        <i class="fas fa-edit"></i> {{ __('Edit') }}
                                    </a>
                                @endif
                                <a href="{{ route('admin.branch.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> {{ __('Back') }}
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="30%">{{ __('Branch Name') }}:</th>
                                            <td>{{ $branch->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Service') }}:</th>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ $branch->service->translation?->title ?? 'N/A' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Status') }}:</th>
                                            <td>
                                                <span class="badge {{ $branch->status ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $branch->status ? __('Active') : __('Inactive') }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Order') }}:</th>
                                            <td>{{ $branch->order }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="30%">{{ __('City') }}:</th>
                                            <td>{{ $branch->city }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Province') }}:</th>
                                            <td>{{ $branch->province }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Created') }}:</th>
                                            <td>{{ $branch->created_at->format('d M Y, H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Updated') }}:</th>
                                            <td>{{ $branch->updated_at->format('d M Y, H:i') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12">
                                    <h5>{{ __('Address') }}</h5>
                                    <div class="alert alert-light">
                                        {{ $branch->address }}
                                    </div>
                                </div>
                            </div>

                            @if($branch->description)
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <h5>{{ __('Description') }}</h5>
                                        <div class="alert alert-light">
                                            {{ $branch->description }}
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($branch->map)
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <h5>{{ __('Map') }}</h5>
                                        <div class="alert alert-light">
                                            @if(str_contains($branch->map, '<iframe'))
                                                {!! $branch->map !!}
                                            @else
                                                <a href="{{ $branch->map }}" target="_blank" class="btn btn-outline-primary">
                                                    <i class="fas fa-map-marker-alt"></i> {{ __('View on Map') }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
