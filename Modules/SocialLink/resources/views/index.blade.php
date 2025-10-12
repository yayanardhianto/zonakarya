@extends('admin.master_layout')
@section('title')
    <title>{{ __('Social Links') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">

            <x-admin.breadcrumb title="{{ __('Social Links') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Social Links') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Social Links')" />
                                <div>
                                    <x-admin.add-button :href="route('admin.social-link.create')" />
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive max-h-400">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Icon') }}</th>
                                                <th>{{ __('Link') }}</th>
                                                <th class="text-center">{{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($socialLinks as $link)
                                                <tr>
                                                    <td>{{ $loop->index + 1 }}</td>
                                                    <td class="bg-transparent-black"><img class="thumb p-2 w_60px"
                                                            src="{{ asset($link->icon) }}" alt=""></td>
                                                    <td>{{ $link->link }}</td>
                                                    <td class="text-center">
                                                        <div>
                                                            <x-admin.edit-button :href="route('admin.social-link.edit', $link->id)" />
                                                            <a href="{{ route('admin.social-link.destroy', $link->id) }}"
                                                                data-modal="#deleteModal"
                                                                class="delete-btn btn btn-danger btn-sm"><i
                                                                    class="fa fa-trash" aria-hidden="true"></i></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('Social Links')" route="admin.social-link.create"
                                                    create="yes" :message="__('No data found!')" colspan="6" />
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="float-right">
                                    {{ $socialLinks->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <x-admin.delete-modal />
@endsection
@push('css')
    <style>
        .w_60px{
            height: 40px;
            width: 40px;
        }
    </style>
@endpush
