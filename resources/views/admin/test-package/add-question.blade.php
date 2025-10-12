@extends('admin.master_layout')
@section('title', __('Add Question to Package'))

@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>{{ __('Add Question to Package') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></div>
                <div class="breadcrumb-item">{{ __('Add Question to Package') }}</div>
            </div>
        </div>
        <div class="section-body">        
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <h3 class="card-title">{{ __('Add Question to Package') }}: {{ $testPackage->name }}</h3>
                                <a href="{{ route('admin.test-package.show', $testPackage) }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> {{ __('Back to Package') }}
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

                            @if($availableQuestions->count() > 0)
                                <div class="row">
                                    @foreach($availableQuestions as $question)
                                        <div class="col-md-6 col-lg-4 mb-4">
                                            <div class="card h-100">
                                                <div class="card-body">
                                                    <h6 class="card-title">{{ Str::limit(strip_tags($question->question_text), 60) }}</h6>
                                                    
                                                    <div class="mb-2">
                                                        @if($question->isMultipleChoice())
                                                            <span class="badge bg-primary">{{ __('Multiple Choice') }}</span>
                                                        @elseif($question->isScale())
                                                            <span class="badge bg-info">{{ __('Scale') }}</span>
                                                        @else
                                                            <span class="badge bg-warning">{{ __('Essay') }}</span>
                                                        @endif
                                                        <span class="badge bg-info">{{ $question->points }} {{ __('points') }}</span>
                                                    </div>

                                                    @if($question->question_image)
                                                        <div class="mb-2">
                                                            <img src="{{ $question->image_url }}" alt="Question Image" 
                                                                class="img-fluid rounded" style="max-height: 100px;">
                                                        </div>
                                                    @endif

                                                    <div class="mb-2">
                                                        <small class="text-muted">
                                                            <strong>{{ __('Options') }}:</strong> {{ $question->options->count() }}
                                                        </small>
                                                    </div>

                                                    @if($question->packages->count() > 0)
                                                        <div class="mb-2">
                                                            <small class="text-muted">
                                                                <strong>{{ __('Used in packages') }}:</strong>
                                                                @foreach($question->packages as $package)
                                                                    <span class="badge bg-secondary">{{ $package->name }}</span>
                                                                @endforeach
                                                            </small>
                                                        </div>
                                                    @endif

                                                    <form action="{{ route('admin.test-package.attach-question', $testPackage) }}" 
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="question_id" value="{{ $question->id }}">
                                                        
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <label for="order_{{ $question->id }}" class="form-label">{{ __('Order') }}</label>
                                                                <input type="number" 
                                                                    class="form-control form-control-sm" 
                                                                    id="order_{{ $question->id }}" 
                                                                    name="order" 
                                                                    value="{{ $testPackage->questions()->count() + 1 }}" 
                                                                    min="1" 
                                                                    required>
                                                            </div>
                                                            <div class="col-6 d-flex align-items-end">
                                                                <button type="submit" class="btn btn-success btn-sm w-100">
                                                                    <i class="fas fa-plus"></i> {{ __('Add to Package') }}
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-info text-center">
                                    <i class="fas fa-info-circle fa-2x mb-3"></i>
                                    <h5>{{ __('No Available Questions') }}</h5>
                                    <p>{{ __('All questions are already added to this package.') }}</p>
                                    <a href="{{ route('admin.test-question.create', ['package_id' => $testPackage->id]) }}" 
                                    class="btn btn-primary">
                                        <i class="fas fa-plus"></i> {{ __('Create New Question') }}
                                    </a>
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
