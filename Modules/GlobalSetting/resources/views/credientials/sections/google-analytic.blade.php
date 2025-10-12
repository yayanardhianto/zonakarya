<div class="tab-pane fade" id="google_analytic_tab" role="tabpanel">
    <form action="{{ route('admin.update-google-analytic') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            @if (config('app.app_mode') == 'DEMO')
                <x-admin.form-input id="google_analytic_id"  name="google_analytic_id" label="{{ __('Measurement ID') }}" value="ANA-34343434-TEST-ID"/>
            @else
                <x-admin.form-input id="google_analytic_id"  name="google_analytic_id" label="{{ __('Measurement ID') }}" value="{{ $setting->google_analytic_id }}"/>
            @endif
        </div>
        <div class="form-group">
            <x-admin.form-switch name="google_analytic_status" label="{{ __('Status') }}" active_value="active" inactive_value="inactive" :checked="$setting->google_analytic_status == 'active'"/>
        </div>

        <x-admin.update-button :text="__('Update')" />
    </form>
</div>