@extends('admin.master_layout')
@section('title')
    <title>{{ __('Database clear') }}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Database clear') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Settings') => route('admin.settings'),
                __('Database clear') => '#',
            ]" />

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="alert alert-warning alert-has-icon">
                                    <div class="alert-icon"><i class="far fa-lightbulb"></i></div>
                                    <div class="alert-body">
                                        <div class="alert-title">{{ __('Warning') }}</div>
                                        {{ __('If you want to use the software from scratch, you have to clear database. You do not need to remove the existing data one by one') }}
                                    </div>
                                </div>
                                <x-admin.button variant="danger" data-bs-toggle="modal" data-bs-target="#clearDatabaseModal" text="{{__('Clear Database')}}"/>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="clearDatabaseModal">
        <div class="modal-dialog" role="document">
            <form class="modal-content" action="{{ route('admin.database-clear-success') }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Clear Database Confirmation') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __('Are you really want to clear this database?') }}</p>
                    <x-admin.form-input type="password" id="password" name="password" label="{{ __('Password') }}" required="true" />
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <x-admin.button variant="danger" data-bs-dismiss="modal" text="{{__('Close')}}"/>
                    <x-admin.button type="submit" text="{{__('Yes, Clear')}}"/>
                </div>
            </form>
        </div>
    </div>
@endsection
