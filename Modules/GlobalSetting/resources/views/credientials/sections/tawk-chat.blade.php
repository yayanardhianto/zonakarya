<div class="tab-pane fade" id="tawk_chat_tab" role="tabpanel">
    <form action="{{ route('admin.update-tawk-chat') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            @if (config('app.app_mode') == 'DEMO')
                <x-admin.form-input id="tawk_chat_link"  name="tawk_chat_link" label="{{ __('Tawk Chat Link') }}" value="https://www.tawk.to/demo-link/34893439"/>
            @else
                <x-admin.form-input id="tawk_chat_link"  name="tawk_chat_link" label="{{ __('Tawk Chat Link') }}" value="{{ $setting->tawk_chat_link }}"/>
            @endif
        </div>
        <div class="form-group">
            <x-admin.form-switch name="tawk_status" label="{{ __('Status') }}" active_value="active" inactive_value="inactive" :checked="$setting->tawk_status == 'active'"/>
        </div>
        <x-admin.update-button :text="__('Update')" />
    </form>
</div>