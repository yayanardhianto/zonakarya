@extends('admin.master_layout')
@section('title', __('Location Management'))

@section('admin-content')

<div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Locations') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></div>
                    <div class="breadcrumb-item">{{ __('Locations') }}</div>
                </div>
            </div>

            <div class="section-body">        
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center w-100">
                                    <h3 class="card-title">{{ __('Location Management') }}</h3>
                                    <a href="{{ route('admin.location.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> {{ __('Add New Location') }}
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                @if(session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session('success') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif

                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('City') }}</th>
                                                <th>{{ __('Province') }}</th>
                                                <th>{{ __('Phone') }}</th>
                                                <th>{{ __('Email') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th>{{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($locations as $location)
                                                <tr>
                                                    <td>{{ $location->name }}</td>
                                                    <td>{{ $location->city }}</td>
                                                    <td>{{ $location->province }}</td>
                                                    <td>{{ $location->phone ?? '-' }}</td>
                                                    <td>{{ $location->email ?? '-' }}</td>
                                                    <td>
                                                        <span class="badge badge-{{ $location->is_active ? 'success' : 'danger' }}">
                                                            {{ $location->is_active ? __('Active') : __('Inactive') }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <a href="{{ route('admin.location.show', $location) }}" 
                                                            class="btn btn-info btn-sm">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <a href="{{ route('admin.location.edit', $location) }}" 
                                                            class="btn btn-warning btn-sm">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <form action="{{ route('admin.location.destroy', $location) }}" 
                                                                method="POST" 
                                                                style="display: inline-block;"
                                                                onsubmit="return confirm('{{ __('Are you sure you want to delete this location?') }}')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center">{{ __('No locations found') }}</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <div class="d-flex justify-content-center">
                                    {{ $locations->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection