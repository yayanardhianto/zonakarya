<div class="tab-pane fade active show" id="general_tab" role="tabpanel">
    <form action="{{ route('admin.update-general-setting') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <x-admin.form-input id="app_name" name="app_name" label="{{ __('App Name') }}"
                value="{{ $setting->app_name }}" />
        </div>
        <div class="form-group">
            <x-admin.form-switch name="preloader_status" label="{{ __('Preloader') }}" :checked="$setting?->preloader_status == '1'" />
        </div>

        <x-admin.update-button :text="__('Update')" />

    </form>
</div>
