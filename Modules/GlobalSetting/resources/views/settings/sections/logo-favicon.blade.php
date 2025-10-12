<div class="tab-pane fade" id="logo_favicon_tab" role="tabpanel">
    <form action="{{ route('admin.update-logo-favicon') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="form-group col-md-4">
                <x-admin.form-image-preview :image="$setting?->logo" name="logo" label="{{ __('Existing Dark Logo') }}"
                    button_label="{{ __('Update Image') }}" required="0"/>
            </div>
            <div class="form-group col-md-4">
                <x-admin.form-image-preview div_id="logo-white-preview" label_id="logo-white-label"
                    input_id="logo-white-upload" :image="$setting?->logo_white" name="logo_white"
                    label="{{ __('Existing White Logo') }}" button_label="{{ __('Update Image') }}" required="0"/>
            </div>

            <div class="form-group col-md-4">
                <x-admin.form-image-preview div_id="favicon-preview" label_id="favicon-label" input_id="favicon-upload"
                    :image="$setting->favicon" name="favicon" label="{{ __('Existing Favicon') }}"
                    button_label="{{ __('Update Image') }}" required="0"/>
            </div>
        </div>

        <x-admin.update-button :text="__('Update')" />
    </form>
</div>
