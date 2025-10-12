@extends('frontend.layouts.master')

@section('meta_title', __('Order') . ' || ' . $setting->app_name)

@section('header')
    @include('frontend.layouts.header-layout.three')
@endsection
@use('App\Enums\OrderStatus', 'OrderStatus')
@section('contents')
    <!-- Breadcumb Area -->
    <x-breadcrumb :title="__('Order')" />

    <!--  Dashboard Area -->
    <section class="wsus__dashboard_profile wsus__dashboard">
        <div class="container">
            <div class="row">
                <!--  Sidebar Area -->
                @include('frontend.profile.partials.sidebar')
                <!--  Main Content Area -->
                <div class="col-lg-8 col-xl-9 ">
                    <div class="wsus__dashboard_main_contant ">
                        <h4>{{ __('Order') }}</h4>
                        <div class="wsus__dashboard_order">
                            <div class="row">
                                <div class="col-12 ">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <th class="serial">{{ __('Serial') }}</th>
                                                    <th class="p_date">{{ __('Purchase Date') }}</th>
                                                    <th class="serial">{{ __('Quantity') }}</th>
                                                    <th class="price">{{ __('Amount') }}</th>
                                                    <th class="package">{{ __('Payment Status') }}</th>
                                                    <th class="price">{{ __('Status') }}</th>
                                                    <th class="action">{{ __('Action') }}</th>
                                                </tr>
                                                @forelse ($orders as $key => $order)
                                                    <tr>
                                                        <td class="serial">{{ $key++ < 10 ? '0' . $key++ : $key++ }}
                                                        </td>
                                                        <td class="p_date">
                                                            {{ formattedDate($order?->created_at) }}
                                                        </td>
                                                        <td class="serial">{{ $order?->product_qty }}</td>
                                                        <td class="price">
                                                            {{ specific_currency_with_icon($order?->payable_currency, $order?->paid_amount) }}
                                                        </td>

                                                        <td class="package"><span
                                                                class="btn-sm rounded-5 btn-{{ OrderStatus::getColor($order?->payment_status) }}">{{ OrderStatus::getLabel($order?->payment_status) }}</span>
                                                        </td>

                                                        <td class="price"><span
                                                                class="btn-sm rounded-5 btn-{{ OrderStatus::getColor($order?->order_status) }}">{{ OrderStatus::getLabel($order?->order_status) }}</span>
                                                        </td>

                                                        <td class="price d-flex gap-1">
                                                            <a target="_blank" class="btn-sm btn-success"
                                                                href="{{ route('user.order.show', $order?->order_id) }}"><i data-bs-toggle="tooltip"
                                                                data-placement="top" title="{{__('Invoice')}}"
                                                                    class="far fa-eye" aria-hidden="true"></i></a>
                                                            @if ($order?->order_status == OrderStatus::DRAFT->value)
                                                                <a class="btn-sm btn-primary"
                                                                    href="{{ route('payment', ['order_id' => $order?->order_id]) }}"><i data-bs-toggle="tooltip"
                                                                data-placement="top" title="{{__('Pay Now')}}"
                                                                        class="fas fa-credit-card"
                                                                        aria-hidden="true"></i></a>
                                                            @endif
                                                            @if (in_array($order?->payment_status, [OrderStatus::COMPLETED->value, OrderStatus::REFUND->value]))
                                                                <a class="btn-sm btn-danger" href="javascript:;"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#refund-{{ $key }}"><i data-bs-toggle="tooltip"
                                                                data-placement="top" title="{{__('Refund Amount')}}"
                                                                        class="fas fa-undo" aria-hidden="true"></i></a>
                                                                <div class="modal fade" id="refund-{{ $key }}"
                                                                    tabindex="-1" aria-hidden="true">
                                                                    <div class="modal-dialog modal-dialog-centered">
                                                                        @if ($order?->refund)
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h4>{{ __('Refund Request') }}</h4>
                                                                                </div>
                                                                                <div class="modal-body text-center">
                                                                                    <p
                                                                                        class="text-{{ OrderStatus::getColor($order?->refund?->status) }}">
                                                                                        {{ $order?->refund?->admin_response ?? __('Your refund request is pending. Please wait for the admin response') }}
                                                                                    </p>
                                                                                    @if ($order?->refund?->status == OrderStatus::COMPLETED->value)
                                                                                        <p><b>{{ __('Refund amount') }}</b>
                                                                                            <span>{{ currency($order?->refund?->refund_amount) }}</span>
                                                                                        </p>
                                                                                    @endif
                                                                                    <span
                                                                                        class="btn-sm rounded-5 btn-{{ OrderStatus::getColor($order?->refund?->status) }}">{{ OrderStatus::getLabel($order?->refund?->status) }}</span>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button"
                                                                                        class="btn py-2 px-3"
                                                                                        data-bs-dismiss="modal">
                                                                                        <span
                                                                                            class="link-effect text-uppercase">
                                                                                            <span
                                                                                                class="effect-1">{{ __('Close') }}</span>
                                                                                            <span
                                                                                                class="effect-1">{{ __('Close') }}</span>
                                                                                        </span>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        @else
                                                                            <form class="modal-content woocommerce-checkout"
                                                                                action="{{ route('user.refund-request', $order?->order_id) }}"
                                                                                method="post">
                                                                                @csrf
                                                                                <div class="modal-header">
                                                                                    <h4>{{ __('Refund Request') }}</h4>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <div class="form-group">
                                                                                        <label>{{ __('Reason') }}
                                                                                            *</label>
                                                                                        <textarea rows="5" class="form-control" placeholder="{{ __('Reason') }}" name="reason">{{ old('reason') }}</textarea>
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <label>{{ __('Payment Method') }}
                                                                                            *</label>
                                                                                        <select class="select_2 form-select"
                                                                                            name="method">
                                                                                            <option value="">
                                                                                                {{ __('Select Method') }}
                                                                                            </option>
                                                                                            @foreach ($methods as $key => $method)
                                                                                                @continue($key == 'hand_cash')
                                                                                                <option
                                                                                                    value="{{ $key }}"
                                                                                                    @selected(old('method') == $key)>
                                                                                                    {{ $method['name'] }}
                                                                                                </option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <label>{{ __('Account Information') }}
                                                                                            *</label>
                                                                                        <textarea rows="5" class="form-control" placeholder="{{ __('Account Information') }}" name="account_information">{{ old('account_information') }}</textarea>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button"
                                                                                        class="btn py-2 px-3"
                                                                                        data-bs-dismiss="modal">
                                                                                        <span
                                                                                            class="link-effect text-uppercase">
                                                                                            <span
                                                                                                class="effect-1">{{ __('Close') }}</span>
                                                                                            <span
                                                                                                class="effect-1">{{ __('Close') }}</span>
                                                                                        </span>
                                                                                    </button>
                                                                                    <button type="submit"
                                                                                        class="btn style2 py-2 px-3">
                                                                                        <span
                                                                                            class="link-effect text-uppercase">
                                                                                            <span
                                                                                                class="effect-1">{{ __('Submit') }}</span>
                                                                                            <span
                                                                                                class="effect-1">{{ __('Submit') }}</span>
                                                                                        </span>
                                                                                    </button>
                                                                                </div>
                                                                            </form>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7"><x-data-not-found /></td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if ($orders->hasPages())
                            {{ $orders->onEachSide(0)->links('frontend.pagination.custom') }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--  Marquee Area -->
    @include('frontend.partials.marquee')
@endsection
@section('footer')
    @include('frontend.layouts.footer-layout.two')
@endsection
