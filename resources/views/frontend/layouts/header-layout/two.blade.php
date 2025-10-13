<!-- Header Area -->
<header class="nav-header header-layout2 bg-white">
    <div class="sticky-wrapper">
        <!-- Main Menu Area -->
        <div class="menu-area">
            <div class="container-fluid">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto">
                        <div class="header-logo">
                            <a href="{{ route('home') }}"><img src="{{ asset($setting?->logo) }}" alt="{{$setting?->app_name}}"></a>
                        </div>
                    </div>
                    <div class="col-auto ms-auto">
                        <nav class="main-menu d-none d-lg-inline-block">
                            @include('frontend.partials.main-menu')
                        </nav>
                        <div class="navbar-right d-inline-flex d-lg-none">
                            
                            <button type="button" class="menu-toggle sidebar-btn" aria-label="hamburger">
                                <i class="fa fa-bars fs-3"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-auto d-none d-lg-block">
                        <div class="header-button">
                            
                            @auth('web')
                                <a href="{{ route('dashboard') }}" class="btn">
                                    <span class="link-effect text-uppercase">
                                        <span class="effect-1">{{ __('Dashboard') }}</span>
                                        <span class="effect-1">{{ __('Dashboard') }}</span>
                                    </span>
                                </a>
                            @else
                                <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#loginModal">
                                    <span class="link-effect text-uppercase">
                                        <span class="effect-1">{{ __('Sign In') }}</span>
                                        <span class="effect-1">{{ __('Sign In') }}</span>
                                    </span>
                                </button>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">{{ __('Sign In') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                

                <form id="loginForm" method="POST" action="{{ route('custom.login') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('Email Address') }}</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">{{ __('Password') }}</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" required autocomplete="current-password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 form-check ps-0">
                        <input type="checkbox" class="form-check-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">
                            {{ __('Remember Me') }}
                        </label>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary" id="loginSubmitBtn">
                            <span id="loginBtnText">{{ __('Sign In') }}</span>
                            <span id="loginBtnSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        </button>
                    </div>
                </form>
                <div class="divider text-center mb-4">
                    <span class="divider-text">{{ __('or') }}</span>
                </div>
                <!-- Social Login Buttons -->
                <div class="social-login mb-4">
                    <a href="{{ route('auth.google') }}" class="btn btn-outline-danger w-100 mb-3 d-flex align-items-center justify-content-center">
                        <svg width="20" height="20" class="me-2" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        {{ __('Continue with Google') }}
                    </a>
                    
                    <a href="{{ route('auth.linkedin') }}" class="btn btn-outline-primary w-100 mb-3 d-flex align-items-center justify-content-center">
                        <svg width="20" height="20" class="me-2" viewBox="0 0 24 24">
                            <path fill="#0077B5" d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                        </svg>
                        {{ __('Continue with LinkedIn') }}
                    </a>
                </div>
                <div class="text-center mt-3">
                    <p class="mb-2">{{ __("Don't have an account?") }}</p>
                    <a href="{{ route('register') }}" class="btn btn-outline-primary btn-sm">
                        {{ __('Create Account') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const loginSubmitBtn = document.getElementById('loginSubmitBtn');
    const loginBtnText = document.getElementById('loginBtnText');
    const loginBtnSpinner = document.getElementById('loginBtnSpinner');
    
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show loading state
            loginSubmitBtn.disabled = true;
            loginBtnText.textContent = '{{ __("Signing In...") }}';
            loginBtnSpinner.classList.remove('d-none');
            
            // Get form data
            const formData = new FormData(loginForm);
        
            // Submit via AJAX
            fetch(loginForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Show success message
                    loginBtnText.textContent = '{{ __("Success! Redirecting...") }}';
                    
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('loginModal'));
                    if (modal) {
                        modal.hide();
                    }
                    
                    // Redirect after short delay
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1000);
                } else {
                    // Show error message
                    alert(data.message || '{{ __("Login failed. Please try again.") }}');
                    
                    // Reset button state
                    loginSubmitBtn.disabled = false;
                    loginBtnText.textContent = '{{ __("Sign In") }}';
                    loginBtnSpinner.classList.add('d-none');
                }
            })
            .catch(error => {
                console.error('Login error:', error);
                alert('{{ __("An error occurred. Please try again.") }}');
                
                // Reset button state
                loginSubmitBtn.disabled = false;
                loginBtnText.textContent = '{{ __("Sign In") }}';
                loginBtnSpinner.classList.add('d-none');
            });
        });
    }
});
</script>

<style>
.social-login .btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.social-login .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.divider {
    position: relative;
    margin: 1.5rem 0;
}

.divider::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 1px;
    background: #e9ecef;
}

.divider-text {
    background: white;
    padding: 0 1rem;
    color: #6c757d;
    font-size: 0.875rem;
    position: relative;
    z-index: 1;
}

#loginModal .modal-dialog {
    max-width: 500px;
}

#loginModal .modal-content {
    border-radius: 8px;
    border: none;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

#loginModal .modal-header {
    border-bottom: 1px solid #e9ecef;
    padding: 1.5rem 2rem 1rem;
}

#loginModal .modal-body {
    padding: 2rem;
}

#loginModal .form-control {
    border-radius: 8px;
    border: 1px solid #dee2e6;
    padding: 0.75rem 1rem;
}

#loginModal .form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

#loginModal .btn-primary {
    border-radius: 8px;
    padding: 0.75rem 1rem;
    font-weight: 500;
}
.btn-outline-danger {
    color: #dc3545 !important;
    border: 2px solid #dc3545 !important;
    background: #ffffff !important;
}
.social-login .btn-outline-primary {
    color: #0777b5 !important;
    border: 2px solid #0777b5 !important;
    background: #ffffff !important;
}

.btn {
    padding: 1rem 1.25rem !important;
    border-radius: 0 !important;
}
</style>
