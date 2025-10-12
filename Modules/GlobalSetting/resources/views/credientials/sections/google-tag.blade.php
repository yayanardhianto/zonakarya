<div class="tab-pane fade" id="googel_tag_tab" role="tabpanel">
    <form action="{{ route('admin.update-google-tag') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            @if (config('app.app_mode') == 'DEMO')
                <x-admin.form-input id="googel_tag_id" name="googel_tag_id" label="{{ __('Google Tag ID') }}"
                    value="ANA-34343434-TEST-ID" />
            @else
                <x-admin.form-input id="googel_tag_id" name="googel_tag_id" label="{{ __('Google Tag ID') }}"
                    value="{{ $setting->googel_tag_id }}" />
            @endif
        </div>
        <div class="form-group">
            <x-admin.form-switch name="googel_tag_status" label="{{ __('Status') }}" active_value="active"
                inactive_value="inactive" :checked="$setting->googel_tag_status == 'active'" />
        </div>
        <x-admin.update-button :text="__('Update')" />
    </form>
</div>
