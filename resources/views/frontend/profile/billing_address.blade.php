@extends('frontend.layouts.master')

@section('meta_title', __('Billing Addresses') . ' || ' . $setting->app_name)

@section('header')
    @include('frontend.layouts.header-layout.three')
@endsection
@section('contents')
    <!-- Breadcumb Area -->
    <x-breadcrumb :title="__('Billing Addresses')" />

    <!--  Dashboard Area -->
    <section class="wsus__dashboard_profile wsus__dashboard">
        <div class="container">
            <div class="row">
                <!--  Sidebar Area -->
                @include('frontend.profile.partials.sidebar')
                <!--  Main Content Area -->
                <div class="col-lg-8 col-xl-9 ">
                    <div class="wsus__dashboard_main_contant ">
                        <div class="wsus__dashboard_order">
                            <div class="row">
                                <div class="col-12 mb-3 d-flex justify-content-between align-items-center">
                                    <h4>{{ __('Billing Addresses') }}</h4>
                                    <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#storeModal" class="btn">
                                        <span class="link-effect">
                                            <span class="effect-1"><i class="fas fa-plus"></i> {{ __('Add New') }}</span>
                                            <span class="effect-1"><i class="fas fa-plus"></i> {{ __('Add New') }}</span>
                                        </span>
                                    </a>
                                </div>
                                <div class="col-12 ">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <th class="serial">{{ __('Serial') }}</th>
                                                    <th class="price">{{ __('Title') }}</th>
                                                    <th class="package">{{ __('Details') }}</th>
                                                    <th class="price">{{ __('Action') }}</th>
                                                </tr>
                                                @forelse ($addresses as $key => $address)
                                                    <tr>
                                                        <td class="serial align-middle">
                                                            {{ $key++ < 10 ? '0' . $key++ : $key++ }}
                                                        </td>
                                                        <td class="price align-middle">{{ $address?->title }}</td>
                                                        <td class="package">
                                                            <address class="mb-0">
                                                                {{ $address?->first_name }} {{ $address?->last_name }}<br>
                                                                {{ $address?->address }}<br>
                                                                {{ $address?->city }}, {{ $address?->province }}
                                                                {{ $address?->zip_code }}<br>
                                                                {{ $address?->country?->name }}<br>
                                                                <strong>{{ __('Phone') }}:</strong>
                                                                {{ $address?->phone }}<br>
                                                                <strong>{{ __('Email') }}:</strong>
                                                                {{ $address?->email }}
                                                            </address>
                                                        </td>

                                                        <td class="price border-bottom-0 align-middle">
                                                            <div
                                                                class=" d-flex gap-2 align-items-center justify-content-center">
                                                                <a class="address-edit btn-sm btn-success"
                                                                    href="javascript:;" data-bs-toggle="modal"
                                                                    data-bs-target="#edit-address-modal"
                                                                    data-address="{{ $address }}"><i
                                                                        class="fas fa-pencil-alt"
                                                                        aria-hidden="true"></i></a>


                                                                <a class="btn-sm btn-danger address-remove"
                                                                    href="javascript:;" data-bs-toggle="modal"
                                                                    data-bs-target="#deleteModal"
                                                                    data-id="{{ $address?->id }}"><i
                                                                        class="far fa-trash-alt" aria-hidden="true"></i></a>
                                                            </div>

                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4"><x-data-not-found /></td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if ($addresses->hasPages())
                            {{ $addresses->onEachSide(0)->links('frontend.pagination.custom') }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--  Add Modal -->
    <div class="modal fade" id="storeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered custom-max-width">
            <form class="modal-content woocommerce-checkout" action="{{ route('user.billing.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h4>{{ __('Store Address') }}</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>{{ __('First Name') }} *</label>
                            <input type="text" class="form-control" placeholder="{{ __('First Name') }}"
                                value="{{ old('first_name') }}" name="first_name">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>{{ __('Last Name') }}</label>
                            <input type="text" class="form-control" placeholder="{{ __('Last Name') }}" name="last_name"
                                value="{{ old('last_name') }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>{{ __('Phone') }} *</label>
                            <input type="text" class="form-control" placeholder="{{ __('Phone') }}" name="phone"
                                value="{{ old('phone') }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>{{ __('Email Address') }} *</label>
                            <input type="email" class="form-control" placeholder="{{ __('Email') }}" name="email"
                                value="{{ old('email') }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>{{ __('Title') }} *</label>
                            <input type="text" class="form-control" placeholder="{{ __('Title') }}" name="title"
                                value="{{ old('title') }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>{{ __('Country') }} *</label>
                            <select class="form-select" name="country_id" id="country_id">
                                @foreach ($countries as $country)
                                    <option @selected(old('country_id') == $country?->id) value="{{ $country?->id }}">
                                        {{ $country?->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>{{ __('Province') }} *</label>
                            <input type="text" class="form-control" name="province" placeholder="{{ __('Province') }}"
                                value="{{ old('province') }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>{{ __('City') }} *</label>
                            <input type="text" class="form-control" name="city" placeholder="{{ __('City') }}"
                                value="{{ old('city') }}">

                        </div>
                        <div class="col-md-6 form-group">
                            <label>{{ __('Zip code') }} *</label>
                            <input type="text" class="form-control" placeholder="{{ __('Zip code') }}"
                                name="zip_code" value="{{ old('zip_code') }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>{{ __('Address') }} <span>*</span></label>
                            <input type="text" class="form-control" placeholder="{{ __('Address') }}" name="address"
                                value="{{ old('address') }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn py-2 px-3" data-bs-dismiss="modal">
                        <span class="link-effect text-uppercase">
                            <span class="effect-1">{{ __('Close') }}</span>
                            <span class="effect-1">{{ __('Close') }}</span>
                        </span>
                    </button>
                    <button type="submit" class="btn style2 py-2 px-3">
                        <span class="link-effect text-uppercase">
                            <span class="effect-1">{{ __('Save') }}</span>
                            <span class="effect-1">{{ __('Save') }}</span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!--  Edit Modal -->
    <div class="modal fade" id="edit-address-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered custom-max-width">
            <form class="modal-content woocommerce-checkout" id="editForm" action="" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h4>{{ __('Update Address') }}</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>{{ __('First Name') }} *</label>
                            <input type="text" class="form-control" placeholder="{{ __('First Name') }}"
                                name="first_name">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>{{ __('Last Name') }}</label>
                            <input type="text" class="form-control" placeholder="{{ __('Last Name') }}"
                                name="last_name">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>{{ __('Phone') }} *</label>
                            <input type="text" class="form-control" placeholder="{{ __('Phone') }}"
                                name="phone">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>{{ __('Email Address') }} *</label>
                            <input type="email" class="form-control" placeholder="{{ __('Email') }}"
                                name="email">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>{{ __('Title') }} *</label>
                            <input type="text" class="form-control" placeholder="{{ __('Title') }}"
                                name="title">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>{{ __('Country') }} *</label>
                            <select class="form-select" name="country_id" id="country_id">
                                @foreach ($countries as $country)
                                    <option value="{{ $country?->id }}">
                                        {{ $country?->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>{{ __('Province') }} *</label>
                            <input type="text" class="form-control" name="province"
                                placeholder="{{ __('Province') }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>{{ __('City') }} *</label>
                            <input type="text" class="form-control" name="city"
                                placeholder="{{ __('City') }}">

                        </div>
                        <div class="col-md-6 form-group">
                            <label>{{ __('Zip code') }} *</label>
                            <input type="text" class="form-control" placeholder="{{ __('Zip code') }}"
                                name="zip_code">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>{{ __('Address') }} <span>*</span></label>
                            <input type="text" class="form-control" placeholder="{{ __('Address') }}"
                                name="address">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn py-2 px-3" data-bs-dismiss="modal">
                        <span class="link-effect text-uppercase">
                            <span class="effect-1">{{ __('Close') }}</span>
                            <span class="effect-1">{{ __('Close') }}</span>
                        </span>
                    </button>
                    <button type="submit" class="btn style2 py-2 px-3">
                        <span class="link-effect text-uppercase">
                            <span class="effect-1">{{ __('Update') }}</span>
                            <span class="effect-1">{{ __('Update') }}</span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!--  Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content woocommerce-checkout" id="deleteForm" action="" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h4>{{ __('Delete Confirmation') }}</h4>
                </div>
                <div class="modal-body">
                    <p>{{ __('Are You sure want to delete this item ?') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn py-2 px-3" data-bs-dismiss="modal">
                        <span class="link-effect text-uppercase">
                            <span class="effect-1">{{ __('Close') }}</span>
                            <span class="effect-1">{{ __('Close') }}</span>
                        </span>
                    </button>
                    <button type="submit" class="btn style2 py-2 px-3">
                        <span class="link-effect text-uppercase">
                            <span class="effect-1">{{ __('Yes, Delete') }}</span>
                            <span class="effect-1">{{ __('Yes, Delete') }}</span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!--  Marquee Area -->
    @include('frontend.partials.marquee')
@endsection
@section('footer')
    @include('frontend.layouts.footer-layout.two')
@endsection
@push('js')
    <script>
        $(document).ready(function() {
            $(document).on("click", ".address-remove", function(e) {
                const id = $(this).data("id");
                $("#deleteForm").attr("action", '{{ url('/user/billing/address') }}' + "/" + id)
            });
            $(document).on("click", ".address-edit", function(e) {
                const data = $(this).data("address");

                $("#editForm input[name='first_name']").val(data.first_name);
                $("#editForm input[name='last_name']").val(data.last_name);
                $("#editForm input[name='phone']").val(data.phone);
                $("#editForm input[name='email']").val(data.email);
                $("#editForm input[name='title']").val(data.title);
                $("#editForm input[name='province']").val(data.province);
                $("#editForm input[name='city']").val(data.city);
                $("#editForm input[name='zip_code']").val(data.zip_code);
                $("#editForm input[name='address']").val(data.address);

                $("#editForm select[name='country_id']").val(data.country_id);

                $("#editForm").attr("action", '{{ url('/user/billing/address') }}' + "/" + data.id)
            });
        });
    </script>
@endpush
