@extends('admin.layouts.app')

@section('title', __('Interviewers Management'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4>{{ __('Interviewers Management') }}</h4>
                        <a href="{{ route('admin.interviewers.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> {{ __('Add Interviewer') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Phone') }}</th>
                                    <th>{{ __('Applications Count') }}</th>
                                    <th>{{ __('Created At') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($interviewers as $interviewer)
                                    <tr>
                                        <td>{{ $interviewer->name }}</td>
                                        <td>{{ $interviewer->email ?: '-' }}</td>
                                        <td>{{ $interviewer->phone ?: '-' }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $interviewer->applications()->count() }}</span>
                                        </td>
                                        <td>{{ $interviewer->created_at->format('d M Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.interviewers.edit', $interviewer) }}"
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i> {{ __('Edit') }}
                                                </a>
                                                <form action="{{ route('admin.interviewers.destroy', $interviewer) }}"
                                                      method="POST"
                                                      class="d-inline"
                                                      onsubmit="return confirm('{{ __('Are you sure you want to delete this interviewer?') }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="fas fa-trash"></i> {{ __('Delete') }}
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-users fa-2x mb-2"></i>
                                                <p>{{ __('No interviewers found') }}</p>
                                                <a href="{{ route('admin.interviewers.create') }}" class="btn btn-primary">
                                                    {{ __('Add First Interviewer') }}
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($interviewers->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $interviewers->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection