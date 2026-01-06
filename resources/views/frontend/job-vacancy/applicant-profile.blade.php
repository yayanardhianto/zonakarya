@extends('frontend.layouts.master')

@section('title', __('Profil Pelamar'))
@push('css')
<style>
    .form-control {
        border: 1px solid rgba(0,0,0,.125)
    }
    .form-control:focus {
        border: 1px solid rgba(0,0,0,.25)
    }   
</style>
@endpush
@section('contents')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
                <div class="card shadow-sm">
                <div class="card-body">
                      <div class="card-header text-center bg-primary text-white pt-5 pb-4 mb-4">
                    @if($skip_test ?? false)
                        <h4 class="mb-3 text-white">{{ __('Untuk menyelesaikan Proses Lamaran, silakan lengkapi profil Anda berikut ini terlebih dahulu.') }}</h4>
                        <p class="text-white">{{ __('Informasi yang Anda isi akan diverifikasi oleh tim HR kami.') }}</p>
                    @else
                        <h4 class="mb-3 text-white">{{ __('Terima kasih telah mengikuti Test Screening, Untuk menyelesaikan Proses Lamaran, silakan lengkapi profil Anda berikut ini terlebih dahulu.') }}</h4>
                        <p class="text-white">{{ __('Hasil test Anda akan diperiksa setelah Anda mengisikan form berikut.') }}</p>
                    @endif
                    </div>


                    <form id="applicantProfileForm" enctype="multipart/form-data">
                        @csrf
                        @if(!($skip_test ?? false))
                            <input type="hidden" id="application_id" name="application_id" value="{{ $application->id }}">
                        @else
                            <input type="hidden" id="job_vacancy_id" name="job_vacancy_id" value="{{ $application->job_vacancy_id }}">
                        @endif

                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('Nama Lengkap') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control border-1" id="name" name="name" value="{{ $applicant->name ?? '' }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="whatsapp" class="form-label">{{ __('Nomor WhatsApp') }} <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="whatsapp" name="whatsapp" value="{{ $applicant->whatsapp ?? '' }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="cv" class="form-label">{{ __('CV/Resume') }} <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="cv" name="cv" accept=".pdf,.doc,.docx,.jpg,.jpeg" required>
                            <small class="form-text text-muted">{{ __('Format yang diterima: PDF, DOC, DOCX, JPG, JPEG (Maks: 25MB)') }}</small>
                        </div>

                        <div class="mb-3">
                            <label for="photo" class="form-label">{{ __('Foto') }} <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="photo" name="photo" accept="image/*" required>
                            <small class="form-text text-muted">{{ __('Format yang diterima: JPG, PNG (Maks: 5MB)') }}</small>
                        </div>

                        <div class="d-flex justify-content-end align-items-center">
                            <button type="button" id="submitProfile" class="btn btn-dark">{{ __('Kirim') }}</button>
                        </div>
                    </form>

                    <hr class="my-4">
                    <small class="text-muted">{{ __('Posisi dilamar') }}: <strong>{{ $job->position ?? '-' }}</strong></small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Simple notification helper (same style as job view)
    function showNotification(message, type = 'warning') {
        const existing = document.querySelectorAll('.form-notification');
        existing.forEach(e => e.remove());
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} form-notification alert-dismissible fade show`;
        notification.setAttribute('role', 'alert');
        notification.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px; max-width: 500px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);';
        const icon = type === 'warning' ? 'fa-exclamation-triangle' : (type === 'success' ? 'fa-check-circle' : 'fa-times-circle');
        notification.innerHTML = `<i class="fas ${icon} me-2"></i><span>${message}</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>`;
        document.body.appendChild(notification);
        setTimeout(() => { notification.classList.remove('show'); setTimeout(() => notification.remove(), 150); }, 5000);
    }

    function validateFile(input, maxSizeMB, allowedTypes, typeName) {
        if (!input || !input.files || input.files.length === 0) {
            return { valid: false, message: `${typeName} harus diisi` };
        }
        const file = input.files[0];
        const fileSizeMB = file.size / (1024 * 1024);
        const fileName = file.name || '';
        const lastDot = fileName.lastIndexOf('.');
        const ext = lastDot > 0 ? fileName.substring(lastDot + 1).toLowerCase() : '';
        if (file.size === 0) return { valid: false, message: `${typeName} kosong atau corrupt.` };
        if (fileSizeMB > maxSizeMB) return { valid: false, message: `${typeName} terlalu besar (${fileSizeMB.toFixed(2)} MB). Maks ${maxSizeMB} MB` };
        let isValid = false;
        const allowedExt = allowedTypes.filter(t => !t.includes('/'));
        const allowedMime = allowedTypes.filter(t => t.includes('/'));
        if (ext && allowedExt.length) isValid = allowedExt.includes(ext.toLowerCase());
        if (!isValid && file.type && allowedMime.length) {
            isValid = allowedMime.some(m => file.type === m || file.type.includes(m.split('/')[1]));
        }
        if (!isValid && !ext) return { valid: false, message: `${typeName} tidak memiliki ekstensi.` };
        if (!isValid) return { valid: false, message: `Format ${typeName} tidak valid (${ext || 'tidak diketahui'})` };
        return { valid: true, message: '' };
    }

    function validateWhatsApp(value) {
        // Validate WhatsApp number - should be at least 10 digits
        const cleaned = value.trim().replace(/\D/g, ''); // Remove all non-digits
        
        if (!value.trim()) {
            return { valid: false, message: 'Nomor WhatsApp harus diisi' };
        }
        
        if (cleaned.length < 10 || cleaned.length > 15) {
            return { valid: false, message: 'Nomor WhatsApp tidak valid (min. 10 digit, maks. 15 digit)' };
        }
        
        // Accept if: starts with 62 (country code), or 0 (local), or + prefix
        const numOnly = cleaned.replace(/\+/g, '');
        if (!/^(62|0)/.test(numOnly)) {
            return { valid: false, message: 'Nomor WhatsApp harus dimulai dengan 62, 0, atau +' };
        }
        
        return { valid: true, message: '' };
    }

    // Prevent accidental back navigation on this page (non-intrusive)
    try {
        history.pushState(null, document.title, window.location.href);
        window.addEventListener('popstate', function (e) {
            // Re-push state and show a small toast — do not block navigation via beforeunload
            history.pushState(null, document.title, window.location.href);
            showNotification('{{ __('Meninggalkan halaman ini tanpa mengisi form, dapat menyebabkan proses lamaran tidak lengkap atau gagal.') }}', 'warning');
        });
    } catch (err) {
        console.warn('Back prevention not available', err);
    }

    // Disable right-click context menu and show notification
    // window.addEventListener('contextmenu', function(e) {
    //     e.preventDefault();
    //     showNotification('{{ __('Klik kanan dinonaktifkan selama proses pendaftaran') }}', 'warning');
    //     return false;
    // });

    // Submit profile (uploads CV + photo and finalize application)
    const submitBtn = document.getElementById('submitProfile');
    const cvEl = document.getElementById('cv');
    const photoEl = document.getElementById('photo');
    const nameEl = document.getElementById('name');
    const whatsappEl = document.getElementById('whatsapp');
    const isSkipTest = {{ $skip_test ?? false ? 'true' : 'false' }};
    const applicationId = document.getElementById('application_id')?.value;
    const jobVacancyId = parseInt(document.getElementById('job_vacancy_id')?.value || '0');
    
    if (submitBtn) {
        submitBtn.addEventListener('click', function() {
            
            if (!isSkipTest && !applicationId) { 
                showNotification('{{ __('Aplikasi tidak ditemukan.') }}', 'warning'); 
                return; 
            }
            
            if (isSkipTest && !jobVacancyId) {
                console.error('jobVacancyId not found', document.getElementById('job_vacancy_id'));
                showNotification('{{ __('Posisi tidak ditemukan.') }}', 'warning'); 
                return;
            }

            // Validate name
            if (!nameEl.value.trim()) { showNotification('{{ __('Nama lengkap harus diisi') }}', 'warning'); return; }
            
            // Validate WhatsApp
            const whatsappV = validateWhatsApp(whatsappEl.value);
            if (!whatsappV.valid) { showNotification(whatsappV.message, 'warning'); return; }

            const cvV = validateFile(cvEl, 25, ['application/pdf','application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document','image/jpeg','image/jpg','pdf','doc','docx','jpg','jpeg'], '{{ __('CV/Resume') }}');
            const photoV = validateFile(photoEl, 5, ['image/jpeg','image/png','image/jpg','jpg','jpeg','png'], '{{ __('Foto') }}');
            if (!cvV.valid) { showNotification(cvV.message, 'warning'); return; }
            if (!photoV.valid) { showNotification(photoV.message, 'warning'); return; }

            submitBtn.disabled = true; submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __('Mengirim...') }}';

            const form = new FormData();
            form.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            form.append('name', nameEl.value.trim());
            form.append('whatsapp', whatsappEl.value.trim());
            form.append('cv', cvEl.files[0]);
            form.append('photo', photoEl.files[0]);

            // Determine endpoint and redirect based on flow
            let url;
            console.log('isSkipTest:', isSkipTest, 'jobVacancyId:', jobVacancyId, 'applicationId:', applicationId);
            if (isSkipTest) {
                url = '/jobs/' + jobVacancyId + '/submit-profile-skip-test';
            } else {
                url = '/applications/' + applicationId + '/finalize';
            }
            
            console.log('Posting to URL:', url);

            fetch(url, {
                method: 'POST',
                body: form,
                credentials: 'same-origin'
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showNotification('{{ __('Lamaran berhasil diselesaikan!') }}', 'success');
                    // clear local session storage keys used earlier
                    sessionStorage.removeItem('pending_application_id');
                    sessionStorage.removeItem('pending_applicant_id');
                    // Redirect to thank-you page
                    if (data.applicant_id) {
                        setTimeout(() => { window.location.href = '{{ route("jobs.thank-you", ":applicant_id") }}'.replace(':applicant_id', data.applicant_id); }, 900);
                    }
                } else {
                    // Handle validation errors
                    if (data.errors) {
                        let errorMessages = [];
                        for (let field in data.errors) {
                            if (Array.isArray(data.errors[field])) {
                                errorMessages.push(...data.errors[field]);
                            } else {
                                errorMessages.push(data.errors[field]);
                            }
                        }
                        const errorText = errorMessages.length > 0 ? errorMessages.join('\n') : (data.message || '{{ __('Kesalahan Validasi') }}');
                        showNotification(errorText, 'danger');
                    } else {
                        showNotification(data.message || '{{ __('Error saat menyelesaikan lamaran') }}', 'danger');
                    }
                }
            })
            .catch(err => { console.error(err); showNotification('{{ __('Terjadi kesalahan. Silakan coba lagi.') }}', 'danger'); })
            .finally(() => { submitBtn.disabled = false; submitBtn.innerHTML = '{{ __('Kirim') }}'; });
        });
    }
});
</script>
@endpush
