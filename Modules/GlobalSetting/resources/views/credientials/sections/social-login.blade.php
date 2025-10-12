<div class="tab-pane fade" id="social_login_tab" role="tabpanel">
    <form action="{{ route('admin.update-social-login') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            @if (config('app.app_mode') == 'DEMO')
                <x-admin.form-input id="gmail_client_id" name="gmail_client_id" label="{{ __('Google Client ID') }}"
                    value="GMAIL-ID-34343-DEMO-CLIENT" />
            @else
                <x-admin.form-input id="gmail_client_id" name="gmail_client_id" label="{{ __('Google Client ID') }}"
                    value="{{ $setting->gmail_client_id }}" />
            @endif
        </div>
        <div class="form-group">
            @if (config('app.app_mode') == 'DEMO')
                <x-admin.form-input id="gmail_secret_id" name="gmail_secret_id" label="{{ __('Google Secret ID') }}"
                    value="GMAIL-ID-343943-TEST-SECRET" />
            @else
                <x-admin.form-input id="gmail_secret_id" name="gmail_secret_id" label="{{ __('Google Secret ID') }}"
                    value="{{ $setting->gmail_secret_id }}" />
            @endif
        </div>
        <div class="form-group">
            <x-admin.form-switch name="google_login_status" label="{{ __('Status') }}" active_value="active"
                inactive_value="inactive" :checked="$setting->google_login_status == 'active'" />
        </div>
        <hr class="my-4">
        
        <!-- LinkedIn Settings -->
        <h5 class="mb-3">{{ __('LinkedIn Login Settings') }}</h5>
        <div class="form-group">
            @if (config('app.app_mode') == 'DEMO')
                <x-admin.form-input id="linkedin_client_id" name="linkedin_client_id" label="{{ __('LinkedIn Client ID') }}"
                    value="LINKEDIN-ID-34343-DEMO-CLIENT" />
            @else
                <x-admin.form-input id="linkedin_client_id" name="linkedin_client_id" label="{{ __('LinkedIn Client ID') }}"
                    value="{{ $setting->linkedin_client_id }}" />
            @endif
        </div>
        <div class="form-group">
            @if (config('app.app_mode') == 'DEMO')
                <x-admin.form-input id="linkedin_client_secret" name="linkedin_client_secret" label="{{ __('LinkedIn Client Secret') }}"
                    value="LINKEDIN-SECRET-343943-TEST-SECRET" />
            @else
                <x-admin.form-input id="linkedin_client_secret" name="linkedin_client_secret" label="{{ __('LinkedIn Client Secret') }}"
                    value="{{ $setting->linkedin_client_secret }}" />
            @endif
        </div>
        <div class="form-group">
            <x-admin.form-switch name="linkedin_login_status" label="{{ __('LinkedIn Status') }}" active_value="active"
                inactive_value="inactive" :checked="$setting->linkedin_login_status == 'active'" />
        </div>
        <x-admin.update-button :text="__('Update')" />

    </form>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="form-group">
                <label>{{ __('Google Redirect Url') }} <span data-toggle="tooltip" data-placement="top"
                        class="fa fa-info-circle text--primary"
                        title="{{ __('Copy the Google login URL and paste it wherever you need to use it.') }}"></span></label>
                <div class="input-group mb-3">
                    <input type="text"
                        value="{{ route('auth.social.callback', \App\Enums\SocialiteDriverType::GOOGLE->value) }}"
                        id="google_redirect_url" class="form-control" readonly>
                    <x-admin.button id="copyGoogleButton" text="{{ __('Copy') }}" />
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>{{ __('LinkedIn Redirect Url') }} <span data-toggle="tooltip" data-placement="top"
                        class="fa fa-info-circle text--primary"
                        title="{{ __('Copy the LinkedIn login URL and paste it wherever you need to use it.') }}"></span></label>
                <div class="input-group mb-3">
                    <input type="text"
                        value="{{ route('auth.social.callback', \App\Enums\SocialiteDriverType::LINKEDIN->value) }}"
                        id="linkedin_redirect_url" class="form-control" readonly>
                    <x-admin.button id="copyLinkedInButton" text="{{ __('Copy') }}" />
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Copy Google URL
    document.getElementById('copyGoogleButton').addEventListener('click', function() {
        const input = document.getElementById('google_redirect_url');
        input.select();
        input.setSelectionRange(0, 99999);
        document.execCommand('copy');
        
        const button = this;
        const originalText = button.textContent;
        button.textContent = '{{ __("Copied!") }}';
        button.classList.add('btn-success');
        
        setTimeout(() => {
            button.textContent = originalText;
            button.classList.remove('btn-success');
        }, 2000);
    });
    
    // Copy LinkedIn URL
    document.getElementById('copyLinkedInButton').addEventListener('click', function() {
        const input = document.getElementById('linkedin_redirect_url');
        input.select();
        input.setSelectionRange(0, 99999);
        document.execCommand('copy');
        
        const button = this;
        const originalText = button.textContent;
        button.textContent = '{{ __("Copied!") }}';
        button.classList.add('btn-success');
        
        setTimeout(() => {
            button.textContent = originalText;
            button.classList.remove('btn-success');
        }, 2000);
    });
});
</script>
