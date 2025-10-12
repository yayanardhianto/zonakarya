@extends('admin.master_layout')
@section('title')
    <title>{{ __('Category List') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            
            <x-admin.breadcrumb title="{{ __('Category List') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Category List') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Category List')" />
                                @adminCan('blog.category.create')
                                    <div>
                                        <x-admin.add-button :href="route('admin.blog-category.create')" />
                                    </div>
                                @endadminCan
                            </div>
                            <div class="card-body">
                                <div class="table-responsive max-h-400">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Slug') }}</th>
                                                @adminCan('blog.category.update')
                                                    <th>{{ __('Status') }}</th>
                                                @endadminCan
                                                @if (checkAdminHasPermission('blog.category.edit') || checkAdminHasPermission('blog.category.delete'))
                                                    <th class="text-center">{{ __('Actions') }}</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($categories as $category)
                                                <tr>
                                                    <td>{{ $loop->index + 1 }}</td>
                                                    <td>{{ $category->title }}</td>
                                                    <td>{{ $category->slug }}</td>
                                                    @adminCan('blog.category.update')
                                                        <td>
                                                            <input class="change-status" data-href="{{route('admin.blog-category.status-update',$category->id)}}"
                                                                id="status_toggle" type="checkbox"
                                                                {{ $category->status ? 'checked' : '' }} data-toggle="toggle"
                                                                data-onlabel="{{ __('Active') }}"
                                                                data-offlabel="{{ __('Inactive') }}" data-onstyle="success"
                                                                data-offstyle="danger">
                                                        </td>
                                                    @endadminCan
                                                    @if (checkAdminHasPermission('blog.category.edit') || checkAdminHasPermission('blog.category.delete'))
                                                        <td class="text-center">
                                                            <div>
                                                                @adminCan('blog.category.edit')
                                                                    <x-admin.edit-button :href="route('admin.blog-category.edit', [
                                                                        'blog_category' => $category->id,
                                                                        'code' => getSessionLanguage(),
                                                                    ])" />
                                                                @endadminCan
                                                                @adminCan('blog.category.delete')
                                                                        <a href="{{route('admin.blog-category.destroy',$category->id)}}" data-modal="#deleteModal" class="delete-btn btn btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                                                @endadminCan
                                                            </div>
                                                        </td>
                                                    @endif
                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('Category')" route="admin.blog-category.create"
                                                    create="yes" :message="__('No data found!')" colspan="5" />
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="float-right">
                                    {{ $categories->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @adminCan('blog.category.delete')
        <x-admin.delete-modal />
    @endadminCan
@endsection
