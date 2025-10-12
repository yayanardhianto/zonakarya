@extends('admin.master_layout')
@section('title', __('Test Category Details'))

@section('admin-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <h3 class="card-title">{{ __('Test Category Details') }}</h3>
                            <div>
                                @if(checkAdminHasPermission('test.category.edit'))
                                    <a href="{{ route('admin.test-category.edit', $testCategory) }}" class="btn btn-warning">
                                        <i class="fas fa-edit"></i> {{ __('Edit') }}
                                    </a>
                                @endif
                                <a href="{{ route('admin.test-category.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> {{ __('Back to Categories') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="150">{{ __('ID') }}:</th>
                                        <td>{{ $testCategory->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Name') }}:</th>
                                        <td>{{ $testCategory->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Description') }}:</th>
                                        <td>{{ $testCategory->description ?: __('No description') }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Status') }}:</th>
                                        <td>
                                            @if($testCategory->is_active)
                                                <span class="badge bg-success">{{ __('Active') }}</span>
                                            @else
                                                <span class="badge bg-danger">{{ __('Inactive') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Created At') }}:</th>
                                        <td>{{ $testCategory->created_at->format('d M Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Updated At') }}:</th>
                                        <td>{{ $testCategory->updated_at->format('d M Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        @if($testCategory->testPackages->count() > 0)
                            <hr>
                            <h5>{{ __('Test Packages in this Category') }}</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>{{ __('ID') }}</th>
                                            <th>{{ __('Package Name') }}</th>
                                            <th>{{ __('Duration') }}</th>
                                            <th>{{ __('Questions') }}</th>
                                            <th>{{ __('Passing Score') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($testCategory->testPackages as $package)
                                            <tr>
                                                <td>{{ $package->id }}</td>
                                                <td>{{ $package->name }}</td>
                                                <td>{{ $package->duration_formatted }}</td>
                                                <td>{{ $package->total_questions }}</td>
                                                <td>{{ $package->passing_score }}%</td>
                                                <td>
                                                    @if($package->is_active)
                                                        <span class="badge bg-success">{{ __('Active') }}</span>
                                                    @else
                                                        <span class="badge bg-danger">{{ __('Inactive') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(checkAdminHasPermission('test.package.view'))
                                                        <a href="{{ route('admin.test-package.show', $package) }}" 
                                                           class="btn btn-sm btn-info">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> {{ __('No test packages found in this category.') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
