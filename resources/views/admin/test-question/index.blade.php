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
                                        <a href="{{ route('admin.test-question.create', ['package_id' => $packageId]) }}" class="btn btn-primary me-2">
                                            <i class="fas fa-plus"></i> {{ __('Add New Question') }}
                                        </a>
                                    @endif
                                    <div class="btn-group me-2">
                                        <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-download me-1"></i> {{ __('Export') }}
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.test-question.export-excel', ['package_id' => $packageId]) }}">
                                                    <i class="fas fa-file-excel text-success  me-1"></i> {{ __('Export to Excel') }}
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.test-question.export-pdf', ['package_id' => $packageId]) }}">
                                                    <i class="fas fa-file-pdf text-danger me-1"></i> {{ __('Export to PDF') }}
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#importModal">
                                        <i class="fas fa-upload"></i> {{ __('Import Questions') }}
                                    </button>
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

                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if($packageId)
                                <div class="alert alert-info alert-dismissible fade show" role="alert">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>{{ __('Filter Active:') }}</strong> 
                                    {{ __('Questions in the selected package are shown at the top. Export will include only questions in the selected package.') }}
                                    <a href="{{ route('admin.test-question.index') }}" class="btn btn-sm btn-primary ms-2">
                                        <i class="fas fa-times"></i> {{ __('Clear Filter') }}
                                    </a>
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
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>{{ __('No') }}</th>
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
                                        @forelse($questions as $index => $question)
                                            @php
                                                $isInPackage = $packageId ? $question->packages->contains($packageId) : false;
                                                $isFirstInPackage = false;
                                                $isFirstNotInPackage = false;
                                                
                                                if ($packageId) {
                                                    if ($index === 0) {
                                                        if ($isInPackage) {
                                                            $isFirstInPackage = true;
                                                        } else {
                                                            $isFirstNotInPackage = true;
                                                        }
                                                    } else {
                                                        $prevQuestion = $questions[$index-1];
                                                        $prevInPackage = $prevQuestion->packages->contains($packageId);
                                                        
                                                        if ($isInPackage && !$prevInPackage) {
                                                            $isFirstInPackage = true;
                                                        } elseif (!$isInPackage && $prevInPackage) {
                                                            $isFirstNotInPackage = true;
                                                        }
                                                    }
                                                }
                                            @endphp
                                            @if($isFirstInPackage)
                                                <tr class="table-success">
                                                    <td colspan="{{ $packageId ? '8' : '7' }}" class="text-center fw-bold">
                                                            <i class="fas fa-check-circle me-1"></i> {{ __('QUESTIONS IN THIS PACKAGE') }}
                                                    </td>
                                                </tr>
                                            @endif
                                            @if($isFirstNotInPackage)
                                                <tr class="table-warning">
                                                    <td colspan="{{ $packageId ? '8' : '7' }}" class="text-center fw-bold">
                                                        <i class="fas fa-info-circle me-1"></i> {{ __('QUESTIONS NOT IN THIS PACKAGE') }}
                                                    </td>
                                                </tr>
                                            @endif
                                            <tr class="{{ $isInPackage ? 'table-success' : '' }}">
                                                <td>{{ $questions->firstItem() + $index }}</td>
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
                                                <td>
                                                    @if($question->isForcedChoice())
                                                        @php
                                                            $traits = $question->getForcedChoiceTraits();
                                                        @endphp
                                                        {{ count($traits) }} traits
                                                    @else
                                                        {{ $question->options->count() }}
                                                    @endif
                                                </td>
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

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">{{ __('Import Test Questions') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.test-question.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>{{ __('Import Format:') }}</strong>
                        <ul class="mb-0 mt-2">
                            <li>{{ __('Supported formats: CSV, Excel (.xlsx, .xls)') }}</li>
                            <li>{{ __('Maximum file size: 2MB') }}</li>
                            <li>{{ __('Supported question types: essay, multiple_choice') }}</li>
                        </ul>
                    </div>

                    <div class="form-group mb-3">
                        <label for="import_file" class="form-label">{{ __('Select File') }} <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="import_file" name="import_file" accept=".csv,.xlsx,.xls" required>
                        <small class="form-text text-muted">
                            {{ __('Download template:') }} 
                            <a href="{{ asset('templates/test-questions-import-template.csv') }}" download class="text-primary me-2">
                                <i class="fas fa-download me-1"></i> {{ __('CSV Template') }}
                            </a>
                            <a href="{{ asset('templates/test-questions-import-template.xlsx') }}" download class="text-success me-2">
                                <i class="fas fa-download me-1"></i> {{ __('Excel Template') }}
                            </a>
                            <!-- <a href="{{ route('admin.test-question.download-excel-template') }}" class="text-info">
                                <i class="fas fa-download"></i> {{ __('Excel Template (Dynamic)') }}
                            </a> -->
                        </small>
                    </div>

                    @if($packageId)
                        <div class="form-group mb-3">
                            <label for="package_id" class="form-label">{{ __('Add to Package') }}</label>
                            <select class="form-select" id="package_id" name="package_id">
                                <option value="">{{ __('No package (import as standalone questions)') }}</option>
                                @foreach($packages as $package)
                                    <option value="{{ $package->id }}" {{ $packageId == $package->id ? 'selected' : '' }}>
                                        {{ $package->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>{{ __('File Format:') }}</strong>
                        <br>
                        <code>question_text,question_type,points,option_1,option_2,option_3,option_4,correct_answer</code>
                        <br><br>
                        <strong>{{ __('Example:') }}</strong>
                        <br>
                        <code>"Apa itu komunikasi?","essay",5,"","","","",""</code><br>
                        <code>"Pilih jawaban benar","multiple_choice",3,"A","B","C","D","1"</code>
                        <br><br>
                        <strong>{{ __('Note:') }}</strong> {{ __('Excel template includes sample data and detailed instructions.') }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload"></i> {{ __('Import Questions') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
