@extends('admin.master_layout')
@section('title')
    <title>{{ __('Testimonials') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Testimonials') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Testimonials') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Testimonials')" />
                                <div>
                                    @adminCan('testimonial.create')
                                        <x-admin.add-button :href="route('admin.testimonial.create')" />
                                    @endadminCan
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive max-h-400">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Designation') }}</th>
                                                <th>{{ __('Image') }}</th>
                                                @adminCan('testimonial.update')
                                                    <th>{{ __('Status') }}</th>
                                                @endadminCan
                                                @if (checkAdminHasPermission('testimonial.edit') || checkAdminHasPermission('testimonial.delete'))
                                                    <th>{{ __('Action') }}</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($testimonials as $testimonial)
                                                <tr>
                                                    <td>{{ $loop->index + 1 }}</td>
                                                    <td>{{ $testimonial->name }}</td>
                                                    <td>{{ $testimonial->designation }}</td>
                                                    <td><img src="{{ asset($testimonial?->image ?? $setting?->default_avatar) }}"
                                                            alt="" class="rounded-circle my-2">
                                                    </td>
                                                    @adminCan('testimonial.update')
                                                        <td>
                                                            <input class="change-status" data-href="{{route('admin.testimonial.status-update',$testimonial->id)}}"
                                                                id="status_toggle" type="checkbox"
                                                                {{ $testimonial->status ? 'checked' : '' }}
                                                                data-toggle="toggle" data-onlabel="{{ __('Active') }}"
                                                                data-offlabel="{{ __('Inactive') }}" data-onstyle="success"
                                                                data-offstyle="danger">
                                                        </td>
                                                    @endadminCan
                                                    @if (checkAdminHasPermission('testimonial.edit') || checkAdminHasPermission('testimonial.delete'))
                                                        <td>
                                                            @adminCan('testimonial.edit')
                                                                <x-admin.edit-button :href="route('admin.testimonial.edit', [
                                                                    'testimonial' => $testimonial->id,
                                                                    'code' => getSessionLanguage(),
                                                                ])" />
                                                            @endadminCan
                                                            @adminCan('testimonial.delete')
                                                                <a href="{{ route('admin.testimonial.destroy', $testimonial->id) }}"
                                                                    data-modal="#deleteModal"
                                                                    class="delete-btn btn btn-danger btn-sm"><i
                                                                        class="fa fa-trash" aria-hidden="true"></i></a>
                                                            @endadminCan
                                                        </td>
                                                    @endif
                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('Testimonial')" route="admin.testimonial.create"
                                                    create="yes" :message="__('No data found!')" colspan="6" />
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="float-right">
                                    {{ $testimonials->onEachSide(3)->onEachSide(3)->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @adminCan('testimonial.delete')
        <x-admin.delete-modal />
    @endadminCan
@endsection