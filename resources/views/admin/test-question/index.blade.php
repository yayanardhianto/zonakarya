@extends('admin.master_layout')
@section('title', __('Test Questions'))

@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>{{ __('Test Questions') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></div>
                <div class="breadcrumb-item">{{ __('Test Questions') }}</div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <h3 class="card-title">
                                    {{ __('Test Questions') }}
                                    @if($packageId)
                                        <small class="text-muted">- {{ __('Filtered by Package') }}</small>
                                    @endif
                                </h3>
                                <div>
                                    @if($packageId)
                                        <a href="{{ route('admin.test-package.add-question', $packageId) }}" class="btn btn-success me-2">
                                            <i class="fas fa-plus-circle"></i> {{ __('Add Existing Question') }}
                                        </a>
                                    @endif
                                    @if(checkAdminHasPermission('test.question.create'))
                                        <a href="{{ route('admin.test-question.create', ['package_id' => $packageId]) }}" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> {{ __('Add New Question') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <!-- Filter Form -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <form method="GET" class="d-flex">
                                        <select name="package_id" class="form-select me-2">
                                            <option value="">{{ __('All Packages') }}</option>
                                            @foreach($packages as $package)
                                                <option value="{{ $package->id }}" {{ $packageId == $package->id ? 'selected' : '' }}>
                                                    {{ $package->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="btn btn-outline-primary">{{ __('Filter') }}</button>
                                    </form>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Order') }}</th>
                                            <th>{{ __('Question') }}</th>
                                            <th>{{ __('Type') }}</th>
                                            <th>{{ __('Points') }}</th>
                                            <th>{{ __('Package') }}</th>
                                            <th>{{ __('Options') }}</th>
                                            @if($packageId)
                                                <th>{{ __('In Package') }}</th>
                                            @endif
                                            <th>{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($questions as $question)
                                            <tr>
                                                <td>{{ $question->order }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($question->question_image)
                                                            <img src="{{ $question->image_url }}" alt="Question Image" 
                                                                class="me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                                        @endif
                                                        <span>{{ Str::limit(strip_tags($question->question_text), 60) }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($question->isMultipleChoice())
                                                        <span class="badge bg-primary">{{ __('Multiple Choice') }}</span>
                                                    @elseif($question->isScale())
                                                        <span class="badge bg-success">{{ __('Scale (1-10)') }}</span>
                                                    @elseif($question->isVideoRecord())
                                                        <span class="badge bg-info">{{ __('Video Record') }}</span>
                                                    @elseif($question->isForcedChoice())
                                                        <span class="badge bg-warning">{{ __('Forced Choice') }}</span>
                                                    @else
                                                        <span class="badge bg-warning">{{ __('Essay') }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $question->points }}</td>
                                                <td>
                                                    @if($question->packages->count() > 0)
                                                        @foreach($question->packages as $package)
                                                            <span class="badge bg-secondary me-1">{{ $package->name }}</span>
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted">{{ __('No packages') }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $question->options->count() }}</td>
                                                @if($packageId)
                                                    <td>
                                                        @php
                                                            $isInPackage = $question->packages->contains($packageId);
                                                        @endphp
                                                        @if($isInPackage)
                                                            <span class="badge bg-success">{{ __('Yes') }}</span>
                                                        @else
                                                            <span class="badge bg-secondary">{{ __('No') }}</span>
                                                        @endif
                                                    </td>
                                                @endif
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        @if($packageId)
                                                            @php
                                                                $isInPackage = $question->packages->contains($packageId);
                                                            @endphp
                                                            @if($isInPackage)
                                                                <form action="{{ route('admin.test-package.detach-question', [$packageId, $question->id]) }}" 
                                                                    method="POST" class="d-inline"
                                                                    onsubmit="return confirm('{{ __('Are you sure you want to remove this question from the package?') }}')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm btn-warning" title="{{ __('Remove from Package') }}">
                                                                        <i class="fas fa-minus"></i>
                                                                    </button>
                                                                </form>
                                                            @else
                                                                <form action="{{ route('admin.test-package.attach-question', $packageId) }}" 
                                                                    method="POST" class="d-inline">
                                                                    @csrf
                                                                    <input type="hidden" name="question_id" value="{{ $question->id }}">
                                                                    <input type="hidden" name="order" value="{{ $questions->count() + 1 }}">
                                                                    <button type="submit" class="btn btn-sm btn-success" title="{{ __('Add to Package') }}">
                                                                        <i class="fas fa-plus"></i>
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        @endif
                                                        @if(checkAdminHasPermission('test.question.view'))
                                                            <a href="{{ route('admin.test-question.show', $question) }}" 
                                                            class="btn btn-sm btn-info" title="{{ __('View') }}">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        @endif
                                                        @if(checkAdminHasPermission('test.question.edit'))
                                                            <a href="{{ route('admin.test-question.edit', $question) }}" 
                                                            class="btn btn-sm btn-warning" title="{{ __('Edit') }}">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                        @endif
                                                        @if(checkAdminHasPermission('test.question.delete'))
                                                            <form action="{{ route('admin.test-question.destroy', $question) }}" 
                                                                method="POST" class="d-inline"
                                                                onsubmit="return confirm('{{ __('Are you sure you want to delete this question?') }}')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger" title="{{ __('Delete') }}">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="{{ $packageId ? '8' : '7' }}" class="text-center">{{ __('No questions found') }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-center">
                                {{ $questions->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
