<div class="section-body row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-center">
                <a href="{{ route('admin.project.edit', [
                    'project' => $project?->id,
                    'code' => allLanguages()->first()->code,
                ]) }}"
                    class="m-1 btn {{ Route::is('admin.project.edit') ? 'btn-success' : 'btn-info' }}">{{ __('Update Project') }}</a>
                <a href="{{ route('admin.project.gallery', $project?->id) }}"
                    class="m-1 btn {{ Route::is('admin.project.gallery') ? 'btn-success' : 'btn-info' }}">{{ __('Update Gallery') }}</a>
            </div>
        </div>
    </div>
</div>
