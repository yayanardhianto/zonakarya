@extends('frontend.layouts.master')
@section('title', __('Taking Test'))

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('contents')
    <div id="test-container" class="container-fluid py-4">
        <!-- Start Test Screen -->
        <div id="start-screen" class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header text-center bg-primary text-white p-5 mb-3">
                            @if(isset($session) && ($session->applicant || ($session->package && ($session->package->is_applicant_flow ?? false) || ($session->package->is_screening_test ?? false))))
                            <h4 class="mb-0 text-white">

                                {{ __('Hi, Selamat Datang di Test Screening General, PT Zona Karya Nusantara. Sebelum melanjutkan mengisi Profile dan mengupload CV, silahkan lakukan terlebih dahulu test ini sampai selesai. Semangat, Semoga Sukses!') }}
                            </h4>
                            @else
                            <h3 class="mb-0 text-white">
                                {{ __('Siap Memulai Tes?') }}
                            </h3>
                            @endif
                        </h3>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-4">
                            <h4>{{ $session->package->name }}</h4>
                            <p class="text-muted">{{ $session->package->description }}</p>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <i class="fas fa-clock fa-2x text-primary mb-2"></i>
                                        <h5>{{ __('Durasi') }}</h5>
                                        <p class="mb-0">{{ $session->package->getDurationFormattedWithQuestionTime() }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <i class="fas fa-question-circle fa-2x text-success mb-2"></i>
                                        <h5>{{ __('Pertanyaan') }}</h5>
                                        <p class="mb-0">{{ $session->package->total_questions }} {{ __('pertanyaan') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <i class="fas fa-trophy fa-2x text-warning mb-2"></i>
                                        <h5>{{ __('Nilai Kelulusan') }}</h5>
                                        <p class="mb-0">{{ $session->package->passing_score }}%</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle"></i> {{ __('Instruksi Penting') }}</h6>
                            <ul class="text-start mb-0">
                                <li>{{ __('Anda tidak dapat melakukan pause atau menghentikan tes setelah dimulai') }}</li>
                                <li>{{ __('Pastikan koneksi internet Anda stabil') }}</li>
                                <li>{{ __('Jangan refresh halaman selama tes berlangsung') }}</li>
                                <li>{{ __('Jawab semua pertanyaan sebelum klik Selesai') }}</li>
                            </ul>
                        </div>

                        <button type="button" class="btn btn-success btn-lg" id="start-test-btn">
                            <i class="fas fa-play me-2"></i> {{ __('Mulai Tes') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Test Interface (Hidden Initially) -->
        <div id="test-interface" style="display: none;">
            <!-- Test Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-6" style="margin-top:2.5rem">
                                    <h4 class="mb-1">{{ $session->package->name }}</h4>
                                    <p class="text-muted mb-0">{{ $session->package->category->name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-md-end justify-content-start align-items-center flex-wrap">
                                        <div>
                                            <span class="badge bg-primary fs-7">{{ __('Pertanyaan') }} <span id="current-question-number">1</span> {{ __('dari') }} {{ $session->package->total_questions }}</span>
                                        </div>
                                        <div class="mt-4 me-4">
                                            <div class="text-start">
                                                <div id="timer" class="fs-4 fw-bold text-danger"></div>
                                                <small class="text-muted">{{ __('Waktu Tersisa') }}</small>
                                            </div>
                                        </div>
                                        <div class="mt-4">
                                            <button id="fullscreen-btn" class="btn btn-outline-primary">
                                                <i class="fas fa-expand me-2"></i> {{ __('Layar Penuh') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Progress Bar -->
                            <div class="mt-4">
                                <div class="progress mb-1" style="height: 10px;">
                                    <div class="progress-bar bg-primary" role="progressbar" 
                                         style="width: 0%" id="progress-bar">
                                    </div>
                                </div>
                                <small class="text-muted">{{ __('Progress') }}: <span id="progress-text">0%</span></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <!-- Question Card -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <form id="answer-form">
                            @csrf
                            <input type="hidden" name="question_id" value="{{ $currentQuestion->id }}">
                            
                            <!-- Question Image -->
                            @if($currentQuestion->question_image)
                                <div class="text-center mb-4">
                                    <img src="{{ $currentQuestion->image_url }}" alt="Question Image" 
                                         class="img-fluid rounded" style="max-height: 400px;">
                                </div>
                            @endif

                            <!-- Question Text -->
                            <div class="mb-4 mt-4">
                                <div class="d-flex align-items-center mb-4">
                                    <h5 class="fw-bold me-4 mb-0">{{ __('Pertanyaan') }} {{ $questionNumber }}</h5>
                                    <div>
                                        <span class="badge bg-info position-relative me-2" style="left: 0; top: 0;">{{ __('Poin') }}: {{ $currentQuestion->points }}</span>
                                        @if($currentQuestion->isMultipleChoice())
                                            <span class="badge bg-primary position-relative" style="left: 0; top: 0;">{{ __('Pilihan Ganda') }}</span>
                                        @elseif($currentQuestion->isScale())
                                            <span class="badge bg-success position-relative" style="left: 0; top: 0;">{{ __('Skala (1-10)') }}</span>
                                        @elseif($currentQuestion->isVideoRecord())
                                            <span class="badge bg-info position-relative" style="left: 0; top: 0;">{{ __('Rekam Video') }}</span>
                                        @elseif($currentQuestion->isForcedChoice())
                                            <span class="badge bg-warning position-relative" style="left: 0; top: 0;">{{ __('Forced Choice') }}</span>
                                        @else
                                            <span class="badge bg-secondary position-relative" style="left: 0; top: 0;">{{ __('Essay') }}</span>
                                        @endif
                                    </div>
                                </div>  
                                <div class="question-text fs-5">
                                    @if($currentQuestion->isForcedChoice())
                                        {!! nl2br(e($currentQuestion->getForcedChoiceInstruction())) !!}
                                    @else
                                        {!! nl2br(e($currentQuestion->question_text)) !!}
                                    @endif
                                </div>
                            </div>

                            <!-- Answer Section -->
                            <div class="mb-4">
                                @if($currentQuestion->isMultipleChoice())
                                    <h6 class="fw-bold mb-3">{{ __('Pilih jawaban Anda:') }}</h6>
                                    <div class="options-container">
                                        @foreach($currentQuestion->options as $option)
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="radio" 
                                                       name="selected_option_id" 
                                                       id="option_{{ $option->id }}" 
                                                       value="{{ $option->id }}">
                                                <label class="form-check-label fs-6" for="option_{{ $option->id }}">
                                                    {{ $option->option_text }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                @elseif($currentQuestion->isScale())
                                    <h6 class="fw-bold mb-3">{{ __('Beri nilai dari 1 sampai 10:') }}</h6>
                                    <div class="scale-container">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="scale-labels d-flex justify-content-between mb-2">
                                                    <small class="text-muted">1 ({{ __('Terendah') }})</small>
                                                    <small class="text-muted">10 ({{ __('Tertinggi') }})</small>
                                                </div>
                                                <input type="range" 
                                                       class="form-range scale-slider" 
                                                       name="scale_value" 
                                                       id="scale_value" 
                                                       min="1" 
                                                       max="10" 
                                                       value="5"
                                                       style="background: linear-gradient(to right, #dc3545 0%, #ffc107 50%, #28a745 100%); padding: 0 7px;">
                                                <div class="scale-numbers d-flex justify-content-between mt-1">
                                                    @for($i = 1; $i <= 10; $i++)
                                                        <small class="text-muted">{{ $i }}</small>
                                                    @endfor
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="text-center">
                                                    <div class="scale-display">
                                                        <span class="scale-value fs-1 fw-bold text-primary" id="scale-display">5</span>
                                                        <div class="scale-description">
                                                            <small class="text-muted" id="scale-description">{{ __('Sedang') }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($currentQuestion->isVideoRecord())
                                    <div class="video-record-container">
                                        <div class="text-center mb-4 mt-5">
                                            <!-- <i class="fas fa-video fa-3x text-primary mb-3"></i> -->
                                            <h5 class="text-primary">{{ __('Rekam Video Anda') }}</h5>
                                            <!-- <p class="text-muted">{{ __('Please record a short video sharing your thoughts, experience, or testimonial.') }}</p> -->
                                        </div>
                                        
                                        <div class="video-recorder-wrapper">
                                            <!-- Live Camera Preview -->
                                            <div class="camera-preview-container mb-4" id="cameraPreviewContainer" style="display: none;">
                                                <video id="cameraPreview" class="w-100" style="max-height: 400px; border-radius: 10px;" autoplay muted></video>
                                                <!-- <div class="camera-overlay">
                                                    <div class="recording-indicator" id="recordingIndicator" style="display: none;">
                                                        <i class="fas fa-circle text-danger me-2"></i>
                                                        <span>{{ __('Recording...') }}</span>
                                                    </div>
                                                </div> -->
                                            </div>
                                            
                                            <!-- Recorded Video Preview -->
                                            <div class="video-preview-container mb-3" id="videoPreviewContainer" style="display: none;">
                                                <video id="videoPreview" class="w-100" style="max-height: 400px; border-radius: 10px;" controls></video>
                                            </div>
                                            
                                            <div class="recording-controls text-center">
                                                <button type="button" id="startRecording" class="btn btn-danger btn-lg rounded-circle me-3" style="width: 80px; height: 80px; padding: unset">
                                                    <i class="fas fa-video fa-2x"></i>
                                                </button>
                                                <button type="button" id="stopRecording" class="btn btn-secondary btn-lg rounded-circle me-3" style="width: 80px; height: 80px; padding: unset;display: none;">
                                                    <i class="fas fa-stop fa-2x"></i>
                                                </button>
                                                <button type="button" id="playRecording" class="btn btn-info btn-lg rounded-circle me-3" style="width: 80px; height: 80px; padding: unset;display: none;">
                                                    <i class="fas fa-play fa-2x"></i>
                                                </button>
                                                <button type="button" id="retakeRecording" class="btn btn-warning btn-lg rounded-circle" style="width: 80px; height: 80px; padding: unset; display: none;">
                                                    <i class="fas fa-redo fa-2x"></i>
                                                </button>
                                            </div>
                                            
                                            <div class="recording-status text-center mt-3">
                                                <div id="recordingStatus" class="text-muted">
                                                    {{ __('Klik tombol merah untuk mulai merekam') }}
                                                </div>
                                                <div id="recordingTimer" class="text-danger fw-bold" style="display: none;">
                                                    <i class="fas fa-circle text-danger me-2"></i>
                                                    <span id="timerDisplay">00:00</span>
                                                </div>
                                            </div>
                                            
                                            <input type="hidden" name="video_answer" id="videoAnswer" value="">
                                            
                                            <!-- Fallback text input for unsupported browsers -->
                                            <div id="videoFallback" style="display: none;" class="mt-4">
                                                <div class="alert alert-info">
                                                    <i class="fas fa-info-circle me-2"></i>
                                                    <strong>{{ __('Kamera Tidak Tersedia') }}:</strong> 
                                                    {{ __('Silakan berikan testimoni Anda dalam format teks di bawah ini.') }}
                                                </div>
                                                <textarea class="form-control" 
                                                          name="video_text_fallback" 
                                                          id="videoTextFallback"
                                                          rows="5" 
                                                          placeholder="{{ __('Silakan berikan testimoni Anda di sini...') }}"
                                                          style="resize: vertical;"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($currentQuestion->isForcedChoice())
                                    <!-- Forced Choice Interface -->
                                    <div class="forced-choice-container">
                                        
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th width="60%">Sifat</th>
                                                        <th width="20%" class="text-center">Mirip</th>
                                                        <th width="20%" class="text-center">Tidak Mirip</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $traits = $currentQuestion->getForcedChoiceTraits();
                                                        if (empty($traits)) {
                                                            // Default traits if none provided
                                                            $traits = [
                                                                'Gampangan, Mudah Setuju',
                                                                'Percaya, Mudah Percaya Pada Orang Lain',
                                                                'Petualang, Mengambil Resiko',
                                                                'Toleran, Menghormati'
                                                            ];
                                                        }
                                                    @endphp
                                                    @foreach($traits as $index => $trait)
                                                        <tr>
                                                            <td class="align-middle">
                                                                <strong>{{ $trait }}</strong>
                                                            </td>
                                                            <td class="text-center align-middle">
                                                                <div class="form-check">
                                                                    <input class="form-check-input most-similar" type="radio" 
                                                                           name="most_similar" value="{{ $index }}" 
                                                                           id="most_similar_{{ $index }}">
                                                                    <label class="form-check-label" for="most_similar_{{ $index }}">
                                                                        Mirip
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td class="text-center align-middle">
                                                                <div class="form-check">
                                                                    <input class="form-check-input least-similar" type="radio" 
                                                                           name="least_similar" value="{{ $index }}" 
                                                                           id="least_similar_{{ $index }}">
                                                                    <label class="form-check-label" for="least_similar_{{ $index }}">
                                                                        Tidak Mirip
                                                                    </label>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                        <div class="mt-3">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="alert alert-success" id="most-similar-selected" style="display: none;">
                                                        <i class="fas fa-check-circle"></i> {{ __('Most similar trait selected') }}
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="alert alert-warning" id="least-similar-selected" style="display: none;">
                                                        <i class="fas fa-check-circle"></i> {{ __('Least similar trait selected') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <h6 class="fw-bold mb-3">{{ __('Jawaban Anda:') }}</h6>
                                    <textarea class="form-control" 
                                              name="answer_text" 
                                              rows="8" 
                                              placeholder="{{ __('Silakan berikan jawaban detail Anda di sini...') }}"
                                              style="resize: vertical;"></textarea>
                                @endif
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-between">
                                <div>
                                    <button type="button" id="save-answer" class="btn btn-outline-primary">
                                        <i class="fas fa-save me-2"></i> {{ __('Simpan Jawaban') }}
                                    </button>
                                </div>
                                <div>
                                    <button type="button" id="next-question" class="btn btn-primary">
                                        {{ __('Pertanyaan Selanjutnya') }} <i class="fas fa-arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Test Navigation -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <div>
                                <button type="button" id="prev-question" class="btn btn-outline-secondary" disabled>
                                    <i class="fas fa-arrow-left"></i> {{ __('Sebelumnya') }}
                                </button>
                            </div>
                            <div>
                                <button type="button" id="complete-test" class="btn btn-success">
                                    <i class="fas fa-check"></i> {{ __('Selesaikan Tes') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div> <!-- End test-interface -->
    </div>

    <!-- Video Instructions Modal -->
    @if($currentQuestion->isVideoRecord())
    <div class="modal fade" id="videoInstructionsModal" tabindex="-1" aria-labelledby="videoInstructionsModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white" id="videoInstructionsModalLabel">
                        <i class="fas fa-info-circle me-2"></i>{{ __('Instruksi Sebelum Merekam') }}
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <h6 class="mb-3"><i class="fas fa-video me-2"></i>{{ __('Persiapan Video Recording') }}</h6>
                        <ul class="text-start mb-0">
                            <li class="mb-2">{{ __('Pastikan kamu siap untuk pembuatan Video') }}</li>
                            <li class="mb-2">{{ __('Pastikan ruangan terang dan tidak gelap') }}</li>
                            <li class="mb-2">{{ __('Pastikan kamu sudah menggunakan pakaian yang rapi dan sopan') }}</li>
                        </ul>
                    </div>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>{{ __('Perhatian') }}:</strong> {{ __('Timer tes akan dimulai setelah Anda klik tombol "Mengerti" di bawah ini.') }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-lg" id="understandVideoInstructions">
                        <i class="fas fa-check me-2"></i>{{ __('Mengerti') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 10000;">
        <div id="toastNotification" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-white text-dark border-bottom border-warning">
                <i class="fas fa-exclamation-triangle text-warning me-2" id="toastIcon"></i>
                <strong class="me-auto" id="toastTitle">{{ __('Peringatan') }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body bg-white text-dark" id="toastMessage">
                {{ __('Pertanyaan harus dijawab terlebih dahulu, sebelum melanjutkan ke pertanyaan berikutnya.') }}
            </div>
        </div>
    </div>

    <!-- Auto-save indicator -->
    <div id="auto-save-indicator" class="position-fixed top-0 end-0 p-3" style="z-index: 9999; display: none;">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-save"></i> {{ __('Jawaban tersimpan otomatis') }}
        </div>
    </div>
@endsection

@push('js')
<script>
console.log('Script loaded');

// ===== BACK PREVENTION WITH TOAST ALERT (Run Immediately) =====
// Strategy: Keep pushing state to make history buffer full, preventing back navigation
(function() {
    console.log('Initializing back prevention...');
    
    // Toast notification function for test page
    function showTestToast(message, type = 'warning') {
        console.log('Showing toast:', message);
        // Remove existing toast if any
        const existing = document.querySelector('.test-toast-notification');
        if (existing) existing.remove();

        // Wait for DOM to be ready before creating element
        if (!document.body) {
            setTimeout(() => createToast(), 100);
            return;
        }
        createToast();
        
        function createToast() {
            try {
                const toast = document.createElement('div');
                toast.className = `alert alert-${type} test-toast-notification alert-dismissible fade show`;
                toast.setAttribute('role', 'alert');
                toast.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px; max-width: 500px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);';
                
                const icon = type === 'warning' ? 'fa-exclamation-triangle' : (type === 'success' ? 'fa-check-circle' : 'fa-times-circle');
                toast.innerHTML = `
                    <i class="fas ${icon} me-2"></i>
                    <span>${message}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;
                
                document.body.appendChild(toast);
                
                // Auto-remove after 5 seconds
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.classList.remove('show');
                        setTimeout(() => {
                            if (toast.parentNode) toast.remove();
                        }, 150);
                    }
                }, 5000);
            } catch (err) {
                console.error('Error creating toast:', err);
            }
        }
    }

    // Store that we're on test page
    window.isOnTestPage = true;
    window.testSessionId = '{{ $session->id }}';
    
    // STRATEGY 1: Fill browser history with dummy entries
    // This makes it impossible to back out by filling up the history stack
    for (let i = 0; i < 10; i++) {
        history.pushState({test: true, index: i}, null, window.location.href);
    }
    console.log('Filled history stack with 10 entries');

    // STRATEGY 2: Listen for popstate and immediately push back
    window.addEventListener('popstate', function(e) {
        console.log('Popstate event detected');
        
        // Show toast warning
        showTestToast('{{ __("Selesaikan terlebih dahulu Test Anda. Jika belum menyelesaikan test dan keluar dari halaman ini, maka Anda tidak bisa melamar kembali pada posisi yang sama.") }}', 'warning');
        
        // Immediately push state back to prevent navigation
        history.pushState({test: true}, null, window.location.href);
        console.log('Pushed state back to prevent back navigation');
    }, false);

    console.log('Back prevention initialized successfully');
})();
// ===== END BACK PREVENTION =====

document.addEventListener('DOMContentLoaded', function() {
    console.log('Test page loaded');
    let timeRemaining = {{ $session->package->getTotalDuration() }}; // Start with total duration (package or per-question)
    let currentQuestionTime = null; // Time for current question (if per-question timing is enabled)
    let questionStartTime = null; // When current question was started
    console.log('Initial timeRemaining:', timeRemaining);
    let autoSaveInterval;
    let isFullscreen = false;
    let timerInterval;
    let testStarted = false;
    let videoInstructionsRead = false; // Flag untuk menandai apakah instruksi video sudah dibaca
    
    // Check if we're accessing a specific question (test already started)
    const urlParams = new URLSearchParams(window.location.search);
    const questionParam = urlParams.get('question');
    if (questionParam && parseInt(questionParam) > 1) {
        testStarted = true;
        console.log('Test already started, question:', questionParam);
        
        // Calculate remaining time based on actual elapsed time
        const totalTime = {{ $session->package->getTotalDuration() }};
        const startedAt = new Date("{{ $session->started_at ? $session->started_at->toISOString() : "" }}");
        const now = new Date();
        
        if (startedAt && !isNaN(startedAt.getTime())) {
            const elapsedSeconds = Math.floor((now - startedAt) / 1000);
            timeRemaining = Math.max(totalTime - elapsedSeconds, 0);
            console.log('Elapsed time:', elapsedSeconds, 'seconds');
            console.log('Remaining time:', timeRemaining, 'seconds');
        } else {
            // Fallback: use full duration if started_at is not available
            timeRemaining = totalTime;
            console.log('Using full duration as fallback:', timeRemaining);
        }
    }

    // ===== VARIABLE DECLARATIONS =====
    const startScreen = document.getElementById('start-screen');
    const testInterface = document.getElementById('test-interface');
    const startTestBtn = document.getElementById('start-test-btn');
    const timerElement = document.getElementById('timer');
    const fullscreenBtn = document.getElementById('fullscreen-btn');
    const answerForm = document.getElementById('answer-form');
    const saveAnswerBtn = document.getElementById('save-answer');
    const nextQuestionBtn = document.getElementById('next-question');
    const prevQuestionBtn = document.getElementById('prev-question');
    const completeTestBtn = document.getElementById('complete-test');
    const autoSaveIndicator = document.getElementById('auto-save-indicator');
    const progressBar = document.getElementById('progress-bar');
    const progressText = document.getElementById('progress-text');
    
    console.log('Elements found:', {
        startScreen: !!startScreen,
        testInterface: !!testInterface,
        startTestBtn: !!startTestBtn,
        timerElement: !!timerElement,
        fullscreenBtn: !!fullscreenBtn,
        answerForm: !!answerForm,
        saveAnswerBtn: !!saveAnswerBtn,
        nextQuestionBtn: !!nextQuestionBtn,
        prevQuestionBtn: !!prevQuestionBtn,
        completeTestBtn: !!completeTestBtn,
        autoSaveIndicator: !!autoSaveIndicator
    });
    
    // If test already started, show test interface immediately
    if (testStarted) {
        console.log('Showing test interface immediately');
        if (startScreen) startScreen.style.display = 'none';
        if (testInterface) testInterface.style.display = 'block';
        
        // Update question number display
        if (questionParam) {
            document.getElementById('current-question-number').textContent = questionParam;
            // Update progress bar after updating question number
            updateProgress();
            
            // Enable previous button if not on question 1
            if (parseInt(questionParam) > 1 && prevQuestionBtn) {
                prevQuestionBtn.disabled = false;
            }
            
            // Check if this is the last question
            const totalQuestions = {{ $session->package->total_questions }};
            if (parseInt(questionParam) >= totalQuestions) {
                // Last question, hide next button and show complete button
                if (nextQuestionBtn) {
                    nextQuestionBtn.style.display = 'none';
                }
                if (completeTestBtn) {
                    completeTestBtn.style.display = 'block';
                }
            } else {
                // Not last question, show next button and hide complete button
                if (nextQuestionBtn) {
                    nextQuestionBtn.style.display = 'block';
                }
                if (completeTestBtn) {
                    completeTestBtn.style.display = 'none';
                }
            }
        } else {
            // Update progress bar for question 1
            updateProgress();
            
            // First question, hide complete button
            if (completeTestBtn) {
                completeTestBtn.style.display = 'none';
            }
        }
        
        // Check if current question is video record
        const isVideoQuestion = {{ $currentQuestion->isVideoRecord() ? 'true' : 'false' }};
        if (isVideoQuestion) {
            // Show video instructions modal first
            const videoModal = new bootstrap.Modal(document.getElementById('videoInstructionsModal'));
            videoModal.show();
        } else {
            // Start timer and auto-save for non-video questions
            startTimer();
            startAutoSave();
        }
    }
    
    // Start Test Button
    if (startTestBtn) {
        startTestBtn.addEventListener('click', function() {
            console.log('Start test button clicked');
            startTest();
        });
    }
    
    function startTest() {
        // Hide start screen and show test interface
        startScreen.style.display = 'none';
        testInterface.style.display = 'block';
        
        // Update progress bar
        updateProgress();
        
        // Check if current question is video record
        const isVideoQuestion = {{ $currentQuestion->isVideoRecord() ? 'true' : 'false' }};
        if (isVideoQuestion) {
            // Show video instructions modal first
            const videoModal = new bootstrap.Modal(document.getElementById('videoInstructionsModal'));
            videoModal.show();
        } else {
            // Start timer and auto-save for non-video questions
            if (!testStarted) {
                startTimer();
                startAutoSave();
                testStarted = true;
            }
        }
        
        console.log('Test started');
    }
    
    // Timer functionality
    function updateTimer() {
        console.log('Timer update - timeRemaining:', timeRemaining);
        
        // Check if we're using per-question timing
        if (currentQuestionTime !== null && questionStartTime !== null) {
            const elapsed = Math.floor((Date.now() - questionStartTime) / 1000);
            const remaining = Math.max(currentQuestionTime - elapsed, 0);
            
            if (remaining <= 0) {
                // Question time is up - auto submit current question
                clearInterval(timerInterval);
                alert('{{ __("Time's up for this question! Moving to next question.") }}');
                nextQuestion();
                return;
            }
            
            // Display question time remaining
            const minutes = Math.floor(remaining / 60);
            const seconds = remaining % 60;
            
            if (remaining <= 30) { // 30 seconds warning for question
                timerElement.classList.add('text-danger', 'pulse');
            }
            
            timerElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            return;
        }
        
        // Regular package timing
        if (timeRemaining <= 0) {
            // Time's up - auto submit
            clearInterval(timerInterval);
            alert('{{ __("Time's up! Your test will be submitted automatically.") }}');
            completeTest();
            return;
        }
        
        // Check if timerElement exists
        if (!timerElement) {
            console.error('timerElement is null!');
            return;
        }
        
        // Display only seconds (MM:SS format)
        const minutes = Math.floor(timeRemaining / 60);
        const seconds = timeRemaining % 60;
        
        if (timeRemaining <= 300) { // 5 minutes warning
            timerElement.classList.add('text-danger', 'pulse');
        }
        
        timerElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        timeRemaining--;
    }
    
    function startTimer() {
        // Start timer only if not already running
        if (!timerInterval) {
            timerInterval = setInterval(updateTimer, 1000); // Update every second
            console.log('Timer started');
        }
    }
    
    // Fullscreen functionality
    if (fullscreenBtn) {
        fullscreenBtn.addEventListener('click', function() {
            console.log('Fullscreen button clicked');
        if (!isFullscreen) {
            if (document.documentElement.requestFullscreen) {
                document.documentElement.requestFullscreen();
            } else if (document.documentElement.webkitRequestFullscreen) {
                document.documentElement.webkitRequestFullscreen();
            } else if (document.documentElement.msRequestFullscreen) {
                document.documentElement.msRequestFullscreen();
            }
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }
        }
    });
    
    // Fullscreen change event
    document.addEventListener('fullscreenchange', function() {
        isFullscreen = !!document.fullscreenElement;
        fullscreenBtn.innerHTML = isFullscreen ? 
            '<i class="fas fa-compress"></i> {{ __("Exit Fullscreen") }}' : 
            '<i class="fas fa-expand"></i> {{ __("Fullscreen") }}';
        });
    }
    
    // Auto-save functionality
    async function autoSave() {
        if (!testStarted) return Promise.resolve(); // Don't auto-save before test starts
        
        // Check if this is a video record question
        const videoAnswer = document.getElementById('videoAnswer');
        const videoTextFallback = document.getElementById('videoTextFallback');
        const isVideoQuestion = {{ $currentQuestion->isVideoRecord() ? 'true' : 'false' }};
        
        if (isVideoQuestion) {
            // For video questions, check if video is already uploaded or needs uploading
            if (videoAnswer && videoAnswer.value) {
                console.log('Auto-save: Video question detected, videoAnswer.value:', videoAnswer.value.substring(0, 50) + '...');
                if (videoAnswer.value.startsWith('blob:')) {
                    // Video is still a blob, needs to be uploaded
                    console.log('Auto-save: Uploading video for question {{ $currentQuestion->id }}');
                    try {
                        // Get the blob from the video preview
                        const videoPreview = document.getElementById('videoPreview');
                        if (videoPreview && videoPreview.src) {
                            console.log('Auto-save: Found video preview, fetching blob...');
                            const response = await fetch(videoPreview.src);
                            const blob = await response.blob();
                            console.log('Auto-save: Blob size:', blob.size, 'bytes');
                            
                            // Check if file is too large
                            if (blob.size > 50 * 1024 * 1024) { // 50MB limit
                                console.warn('Auto-save: Video file too large, skipping upload');
                                // Don't upload if too large, just continue with text fallback
                            } else {
                                const videoUrl = await uploadVideo(blob);
                                console.log('Auto-save: Video uploaded successfully, URL:', videoUrl);
                            }
                        } else {
                            console.log('Auto-save: No video preview found');
                        }
                    } catch (error) {
                        console.error('Auto-save: Video upload failed:', error);
                        // Continue with text fallback if video upload fails
                    }
                } else {
                    // Video is already uploaded (has permanent URL)
                    console.log('Auto-save: Video already uploaded for question {{ $currentQuestion->id }}, URL:', videoAnswer.value);
                }
            } else {
                console.log('Auto-save: No video answer found for question {{ $currentQuestion->id }}');
            }
        }
        
        const formData = new FormData(answerForm);
        formData.append('question_id', {{ $currentQuestion->id }});
        formData.append('token', '{{ request('token') }}');
        
        // Debug: Log video answer data
        if (videoAnswer && videoAnswer.value) {
            console.log('Saving video answer:', videoAnswer.value.substring(0, 50) + '...');
        }
        if (videoTextFallback && videoTextFallback.value) {
            console.log('Saving video text fallback:', videoTextFallback.value.substring(0, 50) + '...');
        }
        
        return fetch('{{ route("test.answer", $session) }}?token={{ request('token') }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAutoSaveIndicator();
                updateProgress();
            }
            return data;
        })
        .catch(error => {
            console.error('Auto-save error:', error);
            throw error;
        });
    }
    
    function updateProgress() {
        // Update progress bar based on current question position
        const totalQuestions = {{ $session->package->total_questions }};
        const currentQuestionNumber = parseInt(document.getElementById('current-question-number').textContent);
        
        // Calculate progress based on current question position / total
        const progressPercentage = Math.round((currentQuestionNumber / totalQuestions) * 100);
        
        if (progressBar) {
            progressBar.style.width = progressPercentage + '%';
        }
        if (progressText) {
            progressText.textContent = progressPercentage + '%';
        }
        
        console.log('Progress update:', {
            currentQuestion: currentQuestionNumber,
            totalQuestions: totalQuestions,
            progressPercentage: progressPercentage
        });
    }
    
    // Start auto-save every 30 seconds (only after test starts)
    function startAutoSave() {
        if (!autoSaveInterval) {
            autoSaveInterval = setInterval(autoSave, 30000);
            console.log('Auto-save started');
        }
    }
    
    function showAutoSaveIndicator() {
        autoSaveIndicator.style.display = 'block';
        setTimeout(() => {
            autoSaveIndicator.style.display = 'none';
        }, 3000);
    }
    
    // Function to validate if question is answered
    function validateAnswer() {
        const questionType = '{{ $currentQuestion->question_type ?? "" }}';
        let isAnswered = false;
        
        if (questionType === 'multiple_choice') {
            const selectedOption = document.querySelector('input[name="selected_option_id"]:checked');
            isAnswered = selectedOption !== null;
        } else if (questionType === 'scale') {
            const scaleValue = document.getElementById('scale_value');
            isAnswered = scaleValue && scaleValue.value !== '';
        } else if (questionType === 'video_record') {
            const videoAnswer = document.getElementById('videoAnswer');
            const videoTextFallback = document.getElementById('videoTextFallback');
            const hasVideoAnswer = videoAnswer && videoAnswer.value && videoAnswer.value.trim() !== '';
            const hasTextFallback = videoTextFallback && videoTextFallback.value && videoTextFallback.value.trim() !== '';
            isAnswered = hasVideoAnswer || hasTextFallback;
        } else if (questionType === 'forced_choice') {
            const mostSelected = document.querySelector('input[name="most_similar"]:checked');
            const leastSelected = document.querySelector('input[name="least_similar"]:checked');
            isAnswered = mostSelected !== null && leastSelected !== null && mostSelected.value !== leastSelected.value;
        } else {
            // Essay or text answer
            const answerText = document.querySelector('textarea[name="answer_text"]');
            isAnswered = answerText && answerText.value.trim() !== '';
        }
        
        return isAnswered;
    }
    
    // Toast notification function
    function showToast(message, type = 'warning') {
        const toast = document.getElementById('toastNotification');
        const toastMessage = document.getElementById('toastMessage');
        const toastIcon = document.getElementById('toastIcon');
        const toastTitle = document.getElementById('toastTitle');
        
        // Set message
        toastMessage.textContent = message;
        
        // Set icon and title based on type
        if (type === 'warning') {
            toastIcon.className = 'fas fa-exclamation-triangle text-warning me-2';
            toastTitle.textContent = '{{ __("Peringatan") }}';
            toast.className = 'toast border-warning';
        } else if (type === 'error') {
            toastIcon.className = 'fas fa-times-circle text-danger me-2';
            toastTitle.textContent = '{{ __("Error") }}';
            toast.className = 'toast border-danger';
        } else if (type === 'success') {
            toastIcon.className = 'fas fa-check-circle text-success me-2';
            toastTitle.textContent = '{{ __("Berhasil") }}';
            toast.className = 'toast border-success';
        } else {
            toastIcon.className = 'fas fa-info-circle text-info me-2';
            toastTitle.textContent = '{{ __("Informasi") }}';
            toast.className = 'toast border-info';
        }
        
        // Show toast
        const bsToast = new bootstrap.Toast(toast, {
            autohide: true,
            delay: 3000
        });
        bsToast.show();
    }
    
    // Video instructions modal handler
    @if($currentQuestion->isVideoRecord())
    const understandVideoBtn = document.getElementById('understandVideoInstructions');
    if (understandVideoBtn) {
        understandVideoBtn.addEventListener('click', function() {
            videoInstructionsRead = true;
            const videoModal = bootstrap.Modal.getInstance(document.getElementById('videoInstructionsModal'));
            if (videoModal) {
                videoModal.hide();
            }
            
            // Start timer and auto-save after user understands instructions
            if (!testStarted) {
                startTimer();
                startAutoSave();
                testStarted = true;
            }
        });
    }
    @endif
    
    // Manual save
    if (saveAnswerBtn) {
        saveAnswerBtn.addEventListener('click', function() {
            if (!testStarted) return;
            
            // Validate answer before saving
            if (!validateAnswer()) {
                showToast('{{ __("Pertanyaan harus dijawab terlebih dahulu sebelum menyimpan.") }}', 'warning');
                return;
            }
            
            console.log('Save button clicked');
            autoSave();
        });
    }
    
    // Next question
    if (nextQuestionBtn) {
        nextQuestionBtn.addEventListener('click', function() {
            if (!testStarted) return;
            
            // Validate answer before proceeding
            if (!validateAnswer()) {
                showToast('{{ __("Pertanyaan harus dijawab terlebih dahulu sebelum melanjutkan ke pertanyaan berikutnya.") }}', 'warning');
                return;
            }
            
            console.log('Next question button clicked');
            // Save current answer first and wait for completion
            autoSave().then(() => {
                console.log('Answer saved, navigating to next question');
                // Navigate to next question after saving
                navigateToNextQuestion();
            }).catch(error => {
                console.error('Error saving answer:', error);
                showToast('{{ __("Error saving your answer. Please try again.") }}', 'error');
            });
        });
    }
    
    // Previous question
    if (prevQuestionBtn) {
        prevQuestionBtn.addEventListener('click', function() {
            if (!testStarted) return;
            console.log('Previous question button clicked');
            // Save current answer first and wait for completion
            autoSave().then(() => {
                console.log('Answer saved, navigating to previous question');
                // Navigate to previous question after saving
                navigateToPreviousQuestion();
            }).catch(error => {
                console.error('Error saving answer:', error);
                alert('{{ __("Error saving your answer. Please try again.") }}');
            });
        });
    }
    
    function navigateToNextQuestion() {
        // Get current question number
        const currentQuestionNumber = parseInt(document.getElementById('current-question-number').textContent);
        const totalQuestions = {{ $session->package->total_questions }};
        
        if (currentQuestionNumber < totalQuestions) {
            // Update question number
            document.getElementById('current-question-number').textContent = currentQuestionNumber + 1;
            
            // Update progress
            updateProgress();
            
            // Enable previous button (we're not on first question anymore) - only if time per question is not enabled
            @if(!$session->package->enable_time_per_question)
            if (prevQuestionBtn) {
                prevQuestionBtn.disabled = false;
            }
            @endif
            
            // Load next question via AJAX
            loadNextQuestion(currentQuestionNumber + 1);
        } else {
            // Last question, hide next button and show complete button
            if (nextQuestionBtn) {
            nextQuestionBtn.style.display = 'none';
            }
            if (completeTestBtn) {
            completeTestBtn.style.display = 'block';
            }
        }
    }
    
    function loadNextQuestion(questionNumber) {
        // For now, just reload the page with the next question
        // In a real implementation, you would load the question via AJAX
        window.location.href = '{{ route("test.take", $session) }}?token={{ request('token') }}&question=' + questionNumber;
    }
    
    function setQuestionTime(questionId, timeInSeconds) {
        // Set time for current question if per-question timing is enabled
        if (timeInSeconds && timeInSeconds > 0) {
            currentQuestionTime = timeInSeconds;
            questionStartTime = Date.now();
            console.log('Question time set:', timeInSeconds, 'seconds');
        } else {
            currentQuestionTime = null;
            questionStartTime = null;
            console.log('Using package timing');
        }
    }
    
    function nextQuestion() {
        // Validate answer before proceeding
        if (!validateAnswer()) {
            showToast('{{ __("Pertanyaan harus dijawab terlebih dahulu sebelum melanjutkan ke pertanyaan berikutnya.") }}', 'warning');
            return;
        }
        
        // Check if video is currently recording and stop it
        const wasRecording = autoStopRecordingIfActive();
        
        if (wasRecording) {
            // Show message that recording was stopped
            const recordingStatus = document.getElementById('recordingStatus');
            if (recordingStatus) {
                recordingStatus.innerHTML = `
                    <div class="alert alert-warning d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <div>
                            <strong>{{ __("Recording Stopped") }}</strong>
                            <div class="mt-1">{{ __("Recording was automatically stopped. Video will be uploaded before proceeding to next question.") }}</div>
                        </div>
                    </div>
                `;
            }
            
            // Wait a bit for the recording to stop and upload, then proceed
            setTimeout(() => {
                autoSave().then(() => {
                    navigateToNextQuestion();
                }).catch(error => {
                    console.error('Auto-save failed after stopping recording:', error);
                    // Still proceed to next question even if upload fails
                    navigateToNextQuestion();
                });
            }, 2000); // Wait 2 seconds for upload to complete
        } else {
            // No active recording, proceed normally
            autoSave().then(() => {
                navigateToNextQuestion();
            });
        }
    }
    
    function navigateToPreviousQuestion() {
        // Get current question number
        const currentQuestionNumber = parseInt(document.getElementById('current-question-number').textContent);
        
        if (currentQuestionNumber > 1) {
            // Update question number
            document.getElementById('current-question-number').textContent = currentQuestionNumber - 1;
            
            // Update progress
            updateProgress();
            
            // Disable previous button if we're going to question 1 - only if time per question is not enabled
            @if(!$session->package->enable_time_per_question)
            if (currentQuestionNumber - 1 === 1 && prevQuestionBtn) {
                prevQuestionBtn.disabled = true;
            }
            @endif
            
            // Load previous question via AJAX
            loadPreviousQuestion(currentQuestionNumber - 1);
        }
    }
    
    function loadPreviousQuestion(questionNumber) {
        // For now, just reload the page with the previous question
        // In a real implementation, you would load the question via AJAX
        window.location.href = '{{ route("test.take", $session) }}?token={{ request('token') }}&question=' + questionNumber;
    }
    
    // Complete test
    if (completeTestBtn) {
        completeTestBtn.addEventListener('click', function() {
            if (!testStarted) return;
            console.log('Complete test button clicked');
            
            // Validate answer before completing test
            if (!validateAnswer()) {
                showToast('{{ __("Pertanyaan harus dijawab terlebih dahulu sebelum menyelesaikan tes.") }}', 'warning');
                return;
            }
            
            // Check if current question is video record and has unsaved answer
            const currentQuestionType = '{{ $currentQuestion->question_type ?? "" }}';
            const videoAnswer = document.getElementById('videoAnswer');
            const videoTextFallback = document.getElementById('videoTextFallback');
            
            if (currentQuestionType === 'video_record') {
                // Check if there's a video answer or text fallback that needs to be saved
                const hasVideoAnswer = videoAnswer && videoAnswer.value && videoAnswer.value.trim() !== '';
                const hasTextFallback = videoTextFallback && videoTextFallback.value && videoTextFallback.value.trim() !== '';
                
                console.log('Video record question detected');
                console.log('Has video answer:', hasVideoAnswer);
                console.log('Has text fallback:', hasTextFallback);
                
                if (hasVideoAnswer || hasTextFallback) {
                    console.log('Saving video answer before completing test...');
                    // Save the current video answer first
                    autoSave().then(() => {
                        console.log('Video answer saved successfully, proceeding to complete test');
            if (confirm('{{ __("Are you sure you want to complete the test? You cannot return to make changes.") }}')) {
                completeTest();
            }
                    }).catch(error => {
                        console.error('Error saving video answer:', error);
                        alert('{{ __("Error saving your answer. Please try again.") }}');
                    });
                    return;
                } else {
                    console.log('No video answer to save, proceeding directly to complete test');
                }
            }
            
            if (confirm('{{ __("Are you sure you want to complete the test? You cannot return to make changes.") }}')) {
                completeTest();
            }
        });
    }
    
    function completeTest() {
        console.log('=== completeTest() called ===');
        // Save final answer first
        autoSave().then(() => {
            console.log('=== autoSave complete, calling test.complete endpoint ===');
            // Submit completion after saving
            const completeUrl = '{{ route("test.complete", $session) }}?token={{ request('token') }}';
            console.log('complete() endpoint URL:', completeUrl);
            return fetch(completeUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });
        })
        .then(response => {
            console.log('=== test.complete response received ===', response.status);
            return response.json();
        })
        .then(data => {
            console.log('=== test.complete JSON parsed ===', data);
            console.log('Response data.success:', data.success);
            console.log('Response data.redirect:', data.redirect);
            if (data.success) {
                if (data.redirect) {
                    console.log('=== Redirecting to ===', data.redirect);
                    window.location.href = data.redirect;
                } else {
                    console.log('=== No redirect in response, using fallback ===');
                    const fallbackUrl = '{{ route("test.result", $session) }}?token={{ request('token') }}';
                    console.log('=== Fallback redirect ===', fallbackUrl);
                    window.location.href = fallbackUrl;
                }
            } else {
                console.error('test.complete returned success: false', data.message);
            }
        })
        .catch(error => {
            console.error('=== Complete test error ===:', error);
            // Fallback redirect
            const errorFallback = '{{ route("test.result", $session) }}?token={{ request('token') }}';
            console.log('=== Error fallback redirect ===', errorFallback);
            window.location.href = errorFallback;
        });
    }
    
    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
    });
    
    // document.addEventListener('keydown', function(e) {
    //     // Disable F12, Ctrl+Shift+I, Ctrl+U, Ctrl+S
    //     if (e.key === 'F12' || 
    //         (e.ctrlKey && e.shiftKey && e.key === 'I') ||
    //         (e.ctrlKey && e.key === 'u') ||
    //         (e.ctrlKey && e.key === 's')) {
    //         e.preventDefault();
    //     }
    // });
    
    // Disable copy-paste
    // document.addEventListener('copy', function(e) {
    //     e.preventDefault();
    // });
    
    // document.addEventListener('paste', function(e) {
    //     e.preventDefault();
    // });
    
    // Video recording functionality
    let mediaRecorder;
    let recordedChunks = [];
    let recordingStartTime;
    let recordingTimer;
    
    const startRecordingBtn = document.getElementById('startRecording');
    const stopRecordingBtn = document.getElementById('stopRecording');
    const playRecordingBtn = document.getElementById('playRecording');
    const retakeRecordingBtn = document.getElementById('retakeRecording');
    const cameraPreview = document.getElementById('cameraPreview');
    const cameraPreviewContainer = document.getElementById('cameraPreviewContainer');
    const videoPreview = document.getElementById('videoPreview');
    const videoPreviewContainer = document.getElementById('videoPreviewContainer');
    const recordingStatus = document.getElementById('recordingStatus');
    const recordingTimerElement = document.getElementById('recordingTimer');
    const timerDisplay = document.getElementById('timerDisplay');
    const recordingIndicator = document.getElementById('recordingIndicator');
    const videoAnswer = document.getElementById('videoAnswer');
    
    if (startRecordingBtn) {
        startRecordingBtn.addEventListener('click', startRecording);
        stopRecordingBtn.addEventListener('click', stopRecording);
        playRecordingBtn.addEventListener('click', playRecording);
        retakeRecordingBtn.addEventListener('click', retakeRecording);
        
        // Check camera support on page load
        checkCameraSupport();
    }
    
    function checkCameraSupport() {
        const videoFallback = document.getElementById('videoFallback');
        
        console.log('Checking camera support...');
        console.log('Protocol:', location.protocol);
        console.log('Hostname:', location.hostname);
        console.log('MediaDevices available:', !!navigator.mediaDevices);
        console.log('getUserMedia available:', !!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia));
        
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            console.log('Camera not supported');
            // Show unsupported message and fallback
            recordingStatus.innerHTML = `
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>{{ __('Camera Not Supported') }}:</strong> 
                    {{ __('Your browser does not support camera recording. Please use a modern browser like Chrome, Firefox, or Safari.') }}
                </div>
            `;
            if (startRecordingBtn) startRecordingBtn.style.display = 'none';
            if (videoFallback) videoFallback.style.display = 'block';
            return;
        }
        
        // Only check HTTPS for non-localhost and non-jezit domains
        const allowedDomains = ['localhost', '127.0.0.1', 'ats.jezit.id'];
        const isAllowedDomain = allowedDomains.some(domain => 
            location.hostname === domain || location.hostname.includes(domain)
        );
        
        console.log('Is allowed domain:', isAllowedDomain);
        
        if (location.protocol !== 'https:' && !isAllowedDomain) {
            console.log('HTTPS required for this domain');
            // Show HTTPS required message and fallback
            recordingStatus.innerHTML = `
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>{{ __('HTTPS Required') }}:</strong> 
                    {{ __('Camera access requires HTTPS. Please use HTTPS to access this feature.') }}
                </div>
            `;
            if (startRecordingBtn) startRecordingBtn.style.display = 'none';
            if (videoFallback) videoFallback.style.display = 'block';
            return;
        }
        
        console.log('Camera support check passed');
    }
    
    async function startRecording() {
        console.log('Starting recording...');
        
        // Check if video instructions have been read
        if (!videoInstructionsRead) {
            showToast('{{ __("Silakan baca dan klik 'Mengerti' pada instruksi video terlebih dahulu.") }}', 'warning');
            return;
        }
        
        try {
            // Try with ideal constraints first
            let stream;
            try {
                console.log('Trying with ideal constraints...');
                stream = await navigator.mediaDevices.getUserMedia({ 
                    video: { 
                        width: { ideal: 1280 },
                        height: { ideal: 720 }
                    }, 
                    audio: true 
                });
                console.log('Ideal constraints successful');
            } catch (constraintError) {
                console.log('Ideal constraints failed, trying basic constraints:', constraintError);
                // Fallback to basic constraints
                try {
                    stream = await navigator.mediaDevices.getUserMedia({ 
                        video: true, 
                        audio: true 
                    });
                    console.log('Basic constraints successful');
                } catch (basicError) {
                    console.log('Basic constraints failed, trying video only:', basicError);
                    // Last fallback - video only
                    stream = await navigator.mediaDevices.getUserMedia({ 
                        video: true, 
                        audio: false 
                    });
                    console.log('Video only successful');
                }
            }
            
            // Show camera preview
            if (cameraPreview) cameraPreview.srcObject = stream;
            if (cameraPreviewContainer) cameraPreviewContainer.style.display = 'block';
            
            // Configure MediaRecorder with compression options
            const options = {
                mimeType: 'video/webm;codecs=vp8,opus',
                videoBitsPerSecond: 1000000, // 1 Mbps for smaller file size
                audioBitsPerSecond: 128000   // 128 kbps for audio
            };
            
            // Check if the preferred options are supported
            if (MediaRecorder.isTypeSupported(options.mimeType)) {
                mediaRecorder = new MediaRecorder(stream, options);
            } else {
                // Fallback to default options
                mediaRecorder = new MediaRecorder(stream);
                console.warn('Preferred video codec not supported, using default');
            }
            
            recordedChunks = [];
            
            mediaRecorder.ondataavailable = function(event) {
                if (event.data.size > 0) {
                    recordedChunks.push(event.data);
                }
            };
            
            mediaRecorder.onstop = function() {
                const blob = new Blob(recordedChunks, { type: 'video/webm' });
                const url = URL.createObjectURL(blob);
                if (videoPreview) videoPreview.src = url;
                
                // Upload video to server
                uploadVideo(blob);
                
                // Stop all tracks
                stream.getTracks().forEach(track => track.stop());
                
                // Hide camera preview, show recorded video
                if (cameraPreviewContainer) cameraPreviewContainer.style.display = 'none';
                if (videoPreviewContainer) videoPreviewContainer.style.display = 'block';
                
                // Update UI - show play and retake buttons
                if (startRecordingBtn) startRecordingBtn.style.display = 'none';
                if (stopRecordingBtn) stopRecordingBtn.style.display = 'none';
                if (playRecordingBtn) playRecordingBtn.style.display = 'inline-block';
                if (retakeRecordingBtn) retakeRecordingBtn.style.display = 'inline-block';
                
                if (recordingStatus) recordingStatus.textContent = '{{ __("Recording completed! You can play it back or retake.") }}';
                if (recordingTimerElement) recordingTimerElement.style.display = 'none';
                if (recordingIndicator) recordingIndicator.style.display = 'none';
                
                clearInterval(recordingTimer);
            };
            
            mediaRecorder.start();
            recordingStartTime = Date.now();
            
            // Update UI - show stop button, hide start button
            if (startRecordingBtn) startRecordingBtn.style.display = 'none';
            if (stopRecordingBtn) stopRecordingBtn.style.display = 'inline-block';
            if (playRecordingBtn) playRecordingBtn.style.display = 'none';
            if (retakeRecordingBtn) retakeRecordingBtn.style.display = 'none';
            
            if (recordingStatus) {
                recordingStatus.innerHTML = `
                    <div class="alert alert-info">
                        <i class="fas fa-video me-2"></i>
                        <strong>{{ __("Recording...") }}</strong> {{ __("Click stop when finished") }}
                        <div class="mt-2">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                {{ __("Recording will automatically stop after 5 minutes to prevent large file sizes.") }}
                            </small>
                        </div>
                    </div>
                `;
            }
            if (recordingTimerElement) recordingTimerElement.style.display = 'block';
            if (recordingIndicator) recordingIndicator.style.display = 'block';
            
            // Start timer
            recordingTimer = setInterval(updateVideoTimer, 1000);
            
            // Auto-stop recording after 5 minutes to prevent very large files
            const maxRecordingTime = 5 * 60 * 1000; // 5 minutes in milliseconds
            setTimeout(() => {
                if (mediaRecorder && mediaRecorder.state === 'recording') {
                    console.log('Auto-stopping recording due to time limit');
                    mediaRecorder.stop();
                    
                    // Show warning about time limit
                    const recordingStatus = document.getElementById('recordingStatus');
                    if (recordingStatus) {
                        recordingStatus.innerHTML = `
                            <div class="alert alert-warning d-flex align-items-center">
                                <i class="fas fa-clock me-2"></i>
                                <div>
                                    <strong>{{ __("Recording Time Limit Reached") }}</strong>
                                    <div class="mt-1">{{ __("Recording was automatically stopped after 5 minutes to prevent large file sizes.") }}</div>
                                </div>
                            </div>
                        `;
                    }
                }
            }, maxRecordingTime);
            
        } catch (error) {
            console.error('Error accessing camera:', error);
            
            // Show specific error message based on error type
            let errorMessage = '{{ __("Unable to access camera. Please check your permissions and try again.") }}';
            
            if (error.name === 'NotAllowedError') {
                errorMessage = '{{ __("Camera access denied. Please allow camera permission and try again.") }}';
            } else if (error.name === 'NotFoundError') {
                errorMessage = '{{ __("No camera found. Please connect a camera and try again.") }}';
            } else if (error.name === 'NotReadableError') {
                errorMessage = '{{ __("Camera is being used by another application. Please close other apps using the camera and try again.") }}';
            } else if (error.name === 'OverconstrainedError') {
                errorMessage = '{{ __("Camera constraints cannot be satisfied. Please try with different camera settings.") }}';
            } else if (error.name === 'SecurityError') {
                errorMessage = '{{ __("Camera access blocked due to security restrictions. Please use HTTPS or localhost.") }}';
            }
            
            // Show error in UI with retry button
            recordingStatus.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>{{ __('Camera Error') }}:</strong> ${errorMessage}
                    <br><small class="text-muted">Error: ${error.name} - ${error.message}</small>
                    <div class="mt-2">
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="retryCameraAccess()">
                            <i class="fas fa-redo me-1"></i> {{ __('Try Again') }}
                        </button>
                    </div>
                </div>
            `;
            
            // Show fallback text input
            const videoFallback = document.getElementById('videoFallback');
            if (videoFallback) videoFallback.style.display = 'block';
            
            // Hide recording controls
            if (startRecordingBtn) startRecordingBtn.style.display = 'none';
            if (stopRecordingBtn) stopRecordingBtn.style.display = 'none';
            if (playRecordingBtn) playRecordingBtn.style.display = 'none';
            if (retakeRecordingBtn) retakeRecordingBtn.style.display = 'none';
        }
    }
    
    function stopRecording() {
        if (mediaRecorder && mediaRecorder.state === 'recording') {
            mediaRecorder.stop();
        }
    }
    
    // Retry camera access function
    function retryCameraAccess() {
        console.log('Retrying camera access...');
        // Reset UI
        const recordingStatus = document.getElementById('recordingStatus');
        const startRecordingBtn = document.getElementById('startRecording');
        const videoFallback = document.getElementById('videoFallback');
        
        if (recordingStatus) {
            recordingStatus.innerHTML = '{{ __("Click the red button to start recording") }}';
        }
        if (startRecordingBtn) {
            startRecordingBtn.style.display = 'inline-block';
        }
        if (videoFallback) {
            videoFallback.style.display = 'none';
        }
        
        // Try to start recording again
        startRecording();
    }
    
    function playRecording() {
        if (videoPreview && videoPreview.src) {
            videoPreview.play();
        }
    }
    
    function retakeRecording() {
        // Reset UI - show start button, hide others
        if (startRecordingBtn) startRecordingBtn.style.display = 'inline-block';
        if (stopRecordingBtn) stopRecordingBtn.style.display = 'none';
        if (playRecordingBtn) playRecordingBtn.style.display = 'none';
        if (retakeRecordingBtn) retakeRecordingBtn.style.display = 'none';
        
        // Hide previews
        if (cameraPreviewContainer) cameraPreviewContainer.style.display = 'none';
        if (videoPreviewContainer) videoPreviewContainer.style.display = 'none';
        
        if (recordingStatus) recordingStatus.textContent = '{{ __("Click the red button to start recording") }}';
        if (recordingTimerElement) recordingTimerElement.style.display = 'none';
        if (recordingIndicator) recordingIndicator.style.display = 'none';
        if (videoAnswer) videoAnswer.value = '';
        
        // Clear previous recording
        if (videoPreview && videoPreview.src) {
            URL.revokeObjectURL(videoPreview.src);
            videoPreview.src = '';
        }
        
        // Stop camera if running
        if (cameraPreview && cameraPreview.srcObject) {
            cameraPreview.srcObject.getTracks().forEach(track => track.stop());
            cameraPreview.srcObject = null;
        }
        
        clearInterval(recordingTimer);
    }
    
    function updateVideoTimer() {
        if (!recordingStartTime) return;
        
        const elapsed = Math.floor((Date.now() - recordingStartTime) / 1000);
        const minutes = Math.floor(elapsed / 60);
        const seconds = elapsed % 60;
        
        const timerDisplay = document.getElementById('recordingTimer');
        if (timerDisplay) {
            timerDisplay.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        }
        
        // Show warning when approaching time limit
        const maxTime = 5 * 60; // 5 minutes
        if (elapsed >= maxTime - 60 && elapsed < maxTime) { // Last minute warning
            const recordingStatus = document.getElementById('recordingStatus');
            if (recordingStatus && !recordingStatus.querySelector('.alert-warning')) {
                const currentText = recordingStatus.textContent;
                recordingStatus.innerHTML = `
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>{{ __("Time Warning") }}:</strong> {{ __("Recording will automatically stop in 1 minute to prevent large file sizes.") }}
                    </div>
                    <div>${currentText}</div>
                `;
            }
        }
    }
    
    async function uploadVideo(blob) {
        try {
            // Show upload progress indicator
            showUploadProgress();
            
            // Check file size and warn if too large
            const fileSizeMB = (blob.size / (1024 * 1024)).toFixed(2);
            console.log('Video file size:', fileSizeMB, 'MB');
            
            if (blob.size > 50 * 1024 * 1024) { // 50MB limit
                console.warn('Video file is very large:', fileSizeMB, 'MB');
                updateUploadStatus('{{ __("Video file is large, this may take a while...") }}');
            }
            
            const formData = new FormData();
            formData.append('video', blob, 'testimonial.webm');
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            
            const response = await fetch('/test/upload-video', {
                method: 'POST',
                body: formData
            });
            
            if (response.ok) {
                const result = await response.json();
                if (result.success) {
                    if (videoAnswer) videoAnswer.value = result.video_url;
                    console.log('Video uploaded successfully:', result.video_url);
                    hideUploadProgress();
                    showUploadSuccess();
                    return result.video_url; // Return the URL
                } else {
                    console.error('Video upload failed:', result.message);
                    hideUploadProgress();
                    showUploadError('{{ __("Failed to upload video. Please try again.") }}');
                    throw new Error(result.message);
                }
            } else {
                throw new Error('Upload failed with status: ' + response.status);
            }
        } catch (error) {
            console.error('Error uploading video:', error);
            hideUploadProgress();
            showUploadError('{{ __("Error uploading video. Please try again.") }}');
            throw error; // Re-throw the error
        }
    }
    
    function showUploadProgress() {
        const recordingStatus = document.getElementById('recordingStatus');
        if (recordingStatus) {
            recordingStatus.innerHTML = `
                <div class="alert alert-info d-flex align-items-center">
                    <div class="spinner-border spinner-border-sm me-2" role="status">
                        <span class="visually-hidden">{{ __("Uploading...") }}</span>
                    </div>
                    <div>
                        <strong>{{ __("Uploading Video...") }}</strong>
                        <div class="progress mt-1" style="height: 6px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                 role="progressbar" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
            `;
        }
    }
    
    function updateUploadStatus(message) {
        const recordingStatus = document.getElementById('recordingStatus');
        if (recordingStatus) {
            const alertDiv = recordingStatus.querySelector('.alert');
            if (alertDiv) {
                const strongElement = alertDiv.querySelector('strong');
                if (strongElement) {
                    strongElement.textContent = message;
                }
            }
        }
    }
    
    function hideUploadProgress() {
        const recordingStatus = document.getElementById('recordingStatus');
        if (recordingStatus) {
            const alertDiv = recordingStatus.querySelector('.alert');
            if (alertDiv) {
                alertDiv.remove();
            }
        }
    }
    
    function showUploadSuccess() {
        const recordingStatus = document.getElementById('recordingStatus');
        if (recordingStatus) {
            recordingStatus.innerHTML = `
                <div class="alert alert-success d-flex align-items-center">
                    <i class="fas fa-check-circle me-2"></i>
                    <div>
                        <strong>{{ __("Video Uploaded Successfully!") }}</strong>
                    </div>
                </div>
            `;
        }
    }
    
    function showUploadError(message) {
        const recordingStatus = document.getElementById('recordingStatus');
        if (recordingStatus) {
            recordingStatus.innerHTML = `
                <div class="alert alert-danger d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <div>
                        <strong>{{ __("Upload Failed") }}</strong>
                        <div class="mt-1">${message}</div>
                        <div class="mt-2">
                            <button type="button" class="btn btn-sm btn-outline-danger me-2" onclick="retryVideoUpload()">
                                <i class="fas fa-redo me-1"></i> {{ __("Try Again") }}
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="useTextFallback()">
                                <i class="fas fa-keyboard me-1"></i> {{ __("Use Text Instead") }}
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }
    }
    
    // Helper functions for video upload actions
    function retryVideoUpload() {
        const videoPreview = document.getElementById('videoPreview');
        if (videoPreview && videoPreview.src) {
            fetch(videoPreview.src)
                .then(response => response.blob())
                .then(blob => uploadVideo(blob))
                .catch(error => {
                    console.error('Error retrying video upload:', error);
                    showUploadError('{{ __("Failed to retry upload. Please try recording again.") }}');
                });
        } else {
            showUploadError('{{ __("No video found to retry upload. Please record again.") }}');
        }
    }
    
    function useTextFallback() {
        const videoFallback = document.getElementById('videoFallback');
        if (videoFallback) {
            videoFallback.style.display = 'block';
            videoFallback.focus();
        }
        hideUploadProgress();
    }
    
    function playRecordedVideo() {
        const videoPreview = document.getElementById('videoPreview');
        if (videoPreview) {
            videoPreview.play();
        }
    }
    
    function retakeVideo() {
        // Reset video recording state
        const videoAnswer = document.getElementById('videoAnswer');
        if (videoAnswer) videoAnswer.value = '';
        
        // Show camera controls again
        const startRecordingBtn = document.getElementById('startRecordingBtn');
        const stopRecordingBtn = document.getElementById('stopRecordingBtn');
        const playRecordingBtn = document.getElementById('playRecordingBtn');
        const retakeRecordingBtn = document.getElementById('retakeRecordingBtn');
        const cameraPreviewContainer = document.getElementById('cameraPreviewContainer');
        const videoPreviewContainer = document.getElementById('videoPreviewContainer');
        const recordingStatus = document.getElementById('recordingStatus');
        
        if (startRecordingBtn) startRecordingBtn.style.display = 'inline-block';
        if (stopRecordingBtn) stopRecordingBtn.style.display = 'none';
        if (playRecordingBtn) playRecordingBtn.style.display = 'none';
        if (retakeRecordingBtn) retakeRecordingBtn.style.display = 'none';
        if (cameraPreviewContainer) cameraPreviewContainer.style.display = 'block';
        if (videoPreviewContainer) videoPreviewContainer.style.display = 'none';
        if (recordingStatus) recordingStatus.textContent = '{{ __("Click start to begin recording your video response.") }}';
    }
    
    // Auto-stop recording when next question is clicked
    function autoStopRecordingIfActive() {
        if (mediaRecorder && mediaRecorder.state === 'recording') {
            console.log('Auto-stopping recording before next question...');
            mediaRecorder.stop();
            return true; // Recording was active and stopped
        }
        return false; // No active recording
    }
    
    // Scale slider functionality
    const scaleSlider = document.getElementById('scale_value');
    const scaleDisplay = document.getElementById('scale-display');
    const scaleDescription = document.getElementById('scale-description');
    
    if (scaleSlider && scaleDisplay && scaleDescription) {
        const scaleDescriptions = {
            1: '{{ __("Very Low") }}',
            2: '{{ __("Low") }}',
            3: '{{ __("Below Average") }}',
            4: '{{ __("Below Average") }}',
            5: '{{ __("Moderate") }}',
            6: '{{ __("Above Average") }}',
            7: '{{ __("Above Average") }}',
            8: '{{ __("High") }}',
            9: '{{ __("Very High") }}',
            10: '{{ __("Excellent") }}'
        };
        
        scaleSlider.addEventListener('input', function() {
            const value = this.value;
            scaleDisplay.textContent = value;
            scaleDescription.textContent = scaleDescriptions[value];
            
            // Update slider color based on value
            const percentage = ((value - 1) / 9) * 100;
            this.style.background = `linear-gradient(to right, #dc3545 0%, #ffc107 50%, #28a745 100%)`;
        });
        
        // Initialize display
        scaleDisplay.textContent = scaleSlider.value;
        scaleDescription.textContent = scaleDescriptions[scaleSlider.value];
    }

    // Update progress when user answers questions
    if (answerForm) {
        answerForm.addEventListener('change', function() {
            if (testStarted) {
                updateProgress();
            }
        });
    }
    
    // Cleanup on page unload
    window.addEventListener('beforeunload', function() {
        if (timerInterval) clearInterval(timerInterval);
        if (autoSaveInterval) clearInterval(autoSaveInterval);
    });
    
    // Initialize question time if per-question timing is enabled
    @if($session->package->enable_time_per_question && isset($currentQuestion))
        @php
            $questionTime = $session->package->questions()->where('test_questions.id', $currentQuestion->id)->first();
            $timeInSeconds = $questionTime ? $questionTime->pivot->time_per_question_seconds : null;
        @endphp
    @if($timeInSeconds)
        setQuestionTime({{ $currentQuestion->id }}, {{ $timeInSeconds }});
    @endif
    @endif
    
    // Disable previous button if time per question is enabled
    @if($session->package->enable_time_per_question)
        if (prevQuestionBtn) {
            prevQuestionBtn.style.display = 'none';
            console.log('Previous button disabled - time per question is enabled');
        }
    @endif

    // Forced Choice functionality
    const mostSimilarRadios = document.querySelectorAll('.most-similar');
    const leastSimilarRadios = document.querySelectorAll('.least-similar');
    const mostSimilarAlert = document.getElementById('most-similar-selected');
    const leastSimilarAlert = document.getElementById('least-similar-selected');
    
    // Prevent selecting same trait for both most and least similar
    mostSimilarRadios.forEach((radio, index) => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                // Disable corresponding least similar option
                const correspondingLeast = document.querySelector(`input[name="least_similar"][value="${this.value}"]`);
                if (correspondingLeast && correspondingLeast.checked) {
                    correspondingLeast.checked = false;
                    if (leastSimilarAlert) leastSimilarAlert.style.display = 'none';
                }
                
                // Show confirmation alert
                if (mostSimilarAlert) {
                    mostSimilarAlert.style.display = 'block';
                    mostSimilarAlert.innerHTML = `<i class="fas fa-check-circle"></i> {{ __('Most similar') }}: <strong>${this.closest('tr').querySelector('td strong').textContent}</strong>`;
                }
                
                // Update hidden input for form submission
                let mostSimilarInput = document.querySelector('input[name="forced_choice_most"]');
                if (!mostSimilarInput) {
                    mostSimilarInput = document.createElement('input');
                    mostSimilarInput.type = 'hidden';
                    mostSimilarInput.name = 'forced_choice_most';
                    document.getElementById('answer-form').appendChild(mostSimilarInput);
                }
                mostSimilarInput.value = this.value;
            }
        });
    });
    
    leastSimilarRadios.forEach((radio, index) => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                // Disable corresponding most similar option
                const correspondingMost = document.querySelector(`input[name="most_similar"][value="${this.value}"]`);
                if (correspondingMost && correspondingMost.checked) {
                    correspondingMost.checked = false;
                    if (mostSimilarAlert) mostSimilarAlert.style.display = 'none';
                }
                
                // Show confirmation alert
                if (leastSimilarAlert) {
                    leastSimilarAlert.style.display = 'block';
                    leastSimilarAlert.innerHTML = `<i class="fas fa-check-circle"></i> {{ __('Least similar') }}: <strong>${this.closest('tr').querySelector('td strong').textContent}</strong>`;
                }
                
                // Update hidden input for form submission
                let leastSimilarInput = document.querySelector('input[name="forced_choice_least"]');
                if (!leastSimilarInput) {
                    leastSimilarInput = document.createElement('input');
                    leastSimilarInput.type = 'hidden';
                    leastSimilarInput.name = 'forced_choice_least';
                    document.getElementById('answer-form').appendChild(leastSimilarInput);
                }
                leastSimilarInput.value = this.value;
            }
        });
    });
    
    // Validate forced choice selection before allowing next question
    const nextButton = document.getElementById('next-question');
    if (nextButton && (mostSimilarRadios.length > 0 || leastSimilarRadios.length > 0)) {
        nextButton.addEventListener('click', function(e) {
            const mostSelected = document.querySelector('input[name="most_similar"]:checked');
            const leastSelected = document.querySelector('input[name="least_similar"]:checked');
            
            if (!mostSelected || !leastSelected) {
                e.preventDefault();
                alert('{{ __("Please select both MOST similar and LEAST similar traits before proceeding.") }}');
                return false;
            }
            
            if (mostSelected.value === leastSelected.value) {
                e.preventDefault();
                alert('{{ __("You cannot select the same trait as both MOST and LEAST similar. Please choose different traits.") }}');
                return false;
            }
        });
    }
});

</script>
@endpush

@push('styles')
<style>
    .pulse {
        animation: pulse 1s infinite;
    }
    
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }
    
    .form-check-input:checked + .form-check-label {
        font-weight: bold;
        color: #007bff;
    }
    
    .question-text {
        line-height: 1.6;
    }
    
    .options-container .form-check {
        padding: 10px;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        margin-bottom: 10px;
        transition: all 0.3s ease;
    }
    
    .options-container .form-check:hover {
        background-color: #f8f9fa;
        border-color: #007bff;
    }
    
    .options-container .form-check-input:checked + .form-check-label {
        background-color: #e7f3ff;
    }
    
    #test-container {
        min-height: 100vh;
        background-color: #f8f9fa;
    }
    
    .card {
        border-radius: 12px;
    }
    
    .btn {
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 500;
    }
    
    .progress {
        border-radius: 10px;
    }
    
    .progress-bar {
        border-radius: 10px;
    }
    
    /* Video recording styles */
    .video-record-container {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 30px;
        border: 2px solid #e9ecef;
        margin: 20px 0;
    }
    
    .video-recorder-wrapper {
        max-width: 600px;
        margin: 0 auto;
    }
    
    .recording-controls {
        margin: 30px 0;
    }
    
    .recording-controls .btn {
        margin: 0 10px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }
    
    .recording-controls .btn:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.15);
    }
    
    .recording-controls .btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .camera-preview-container {
        position: relative;
        background: #000;
        border-radius: 10px;
        overflow: hidden;
        margin: 20px 0;
    }
    
    .camera-overlay {
        position: absolute;
        top: 10px;
        left: 10px;
        right: 10px;
        z-index: 10;
    }
    
    .recording-indicator {
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 8px 12px;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: bold;
        display: inline-block;
    }
    
    .video-preview-container {
        background: #000;
        border-radius: 10px;
        overflow: hidden;
        margin: 20px 0;
    }
    
    .recording-status {
        font-size: 1.1rem;
        margin: 20px 0;
    }
    
    .recording-timer {
        font-size: 1.2rem;
        font-weight: bold;
    }
    
    #timerDisplay {
        font-family: 'Courier New', monospace;
    }
    
    /* Mobile responsive */
    @media (max-width: 768px) {
        .video-record-container {
            padding: 20px;
            margin: 10px 0;
        }
        
        .recording-controls .btn {
            width: 60px !important;
            height: 60px !important;
            margin: 5px;
        }
        
        .recording-controls .btn i {
            font-size: 1.2rem !important;
        }
        
        .video-preview-container {
            margin: 15px 0;
        }
        
        .recording-status {
            font-size: 1rem;
        }
    }
    
    @media (max-width: 480px) {
        .recording-controls .btn {
            width: 50px !important;
            height: 50px !important;
            margin: 3px;
        }
        
        .recording-controls .btn i {
            font-size: 1rem !important;
        }
    }
    
    /* Scale slider styles */
    .scale-slider {
        height: 8px;
        border-radius: 5px;
        outline: none;
        -webkit-appearance: none;
        appearance: none;
    }
    
    .scale-slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #007bff;
        cursor: pointer;
        border: 3px solid #fff;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    }
    
    .scale-slider::-moz-range-thumb {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #007bff;
        cursor: pointer;
        border: 3px solid #fff;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    }
    
    .scale-display {
        background: #f8f9fa;
        border: 2px solid #e9ecef;
        border-radius: 15px;
        padding: 20px;
        margin: 10px 0;
    }
    
    .scale-container {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #e9ecef;
    }
    
    .scale-numbers {
        font-size: 0.8rem;
    }
    
    .scale-labels {
        font-weight: 500;
    }
    
    /* Toast Notification Styles */
    .toast-container {
        z-index: 10000;
    }
    
    .toast {
        min-width: 300px;
        max-width: 400px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .toast-header {
        font-weight: 600;
    }
    
    .toast.border-warning {
        border-left: 4px solid #ffc107;
    }
    
    .toast.border-danger {
        border-left: 4px solid #dc3545;
    }
    
    .toast.border-success {
        border-left: 4px solid #28a745;
    }
    
    .toast.border-info {
        border-left: 4px solid #17a2b8;
    }
    
    /* Video Instructions Modal */
    #videoInstructionsModal .modal-content {
        border-radius: 15px;
    }
    
    #videoInstructionsModal .modal-header {
        border-radius: 15px 15px 0 0;
    }
    
    #videoInstructionsModal .modal-body ul {
        padding-left: 1.5rem;
    }
    
    #videoInstructionsModal .modal-body li {
        margin-bottom: 0.75rem;
    }
    
    @media (max-width: 576px) {
        .toast {
            min-width: 250px;
            max-width: 90%;
        }
    }
</style>
@endpush
