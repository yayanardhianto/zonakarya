@extends('frontend.layouts.master')

@section('meta_title', __('Invoice') . ' || ' . $order?->order_id)

@section('header')
    @include('frontend.layouts.header-layout.three')
@endsection

@section('contents')
    <!-- Breadcumb Area -->
    <x-breadcrumb :title="__('Invoice')" />
    <!--  Dashboard Area -->
    <section class="wsus__dashboard_profile wsus__dashboard">
        <div class="container">
            <div class="row">
                <!--  Sidebar Area -->
                @include('frontend.profile.partials.sidebar')
                <!--  Main Content Area -->
                <div class="col-lg-8 col-xl-9 ">
                    <div class="wsus__dashboard_main_contant ">
                        <div class="wsus__dashboard_invoice mt-0">
                            <div class="dashboard_invoice_top px-2">
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <a href="{{ route('user.dashboard') }}" class="wsus__dashboard_invoice_logo">
                                            <img src="{{ asset($setting?->logo) ?? $setting?->app_name }}"
                                                alt="{{ $setting?->app_name ?? '' }}">
                                        </a>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="wsus__dashboard_invoice_left wsus__dashboard_invoice_right">
                                            <h2>{{ __('Invoice') }}</h2>
                                            <h5>{{ __('Order Id') }}: #{{ $order?->order_id }}</h5>
                                            <h5>{{ __('Date') }}: {{ formattedDate($order?->created_at) }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="dashboard_invoice_body">
                                <table class="table table-no-border">
                                    <tbody>
                                        <tr>
                                            <td class="w-50">
                                                <div class="wsus__dashboard_invoice_left">
                                                    <h5>{{ __('Invoice to') }}:</h5>
                                                    <p>{{ $order?->order_address?->billing_first_name . ' ' . $order?->order_address?->billing_last_name }}
                                                    </p>


                                                    <p class="text-lowercase">
                                                        {{ $order?->order_address?->billing_email }}</p>
                                                    <p>{{ $order?->order_address?->billing_phone }}</p>
                                                    <p>{{ $order?->order_address?->billing_address }},
                                                        {{ $order?->order_address?->billing_city }},
                                                        {{ $order?->order_address?->billing_state }},
                                                        {{ $order?->order_address?->billing_country }}.</p>
                                                </div>
                                            </td>
                                            <td class="w-50">
                                                <div class="wsus__dashboard_invoice_left wsus__dashboard_invoice_right">
                                                    <h5>{{ __('Invoice from') }}:
                                                    </h5>
                                                    <p>{{ $setting->app_name }}
                                                    </p>
                                                    <p class="text-lowercase">
                                                        {{ $contactSection?->email }}
                                                    </p>
                                                    <p>{{ $contactSection?->phone }}</p>
                                                    <p>{{ $contactSection?->address }}</p>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>

                                </table>
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <th class="packages">
                                                {{ __('Product Name') }}
                                            </th>
                                            <th class="p_date">
                                                {{ __('Quantity') }}
                                            </th>
                                            <th class="e_date">
                                                {{ __('Unit Price') }}
                                            </th>
                                            <th class="amount">
                                                {{ __('Total Amount') }}
                                            </th>
                                        </tr>
                                        @foreach ($order?->order_products as $product)
                                            <tr>
                                                <td class="packages">
                                                    {{ $product?->product_name }}
                                                </td>
                                                <td class="p_date">
                                                    {{ $product?->qty }}
                                                </td>
                                                <td class="e_date">
                                                    {{ specific_currency_with_icon($order?->payable_currency, $product?->unit_price) }}
                                                </td>
                                                <td class="amount">
                                                    {{ specific_currency_with_icon($order?->payable_currency, $product?->total) }}
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-12 ">
                                    <div class="table-responsive">
                                        <table class="table table-no-border">
                                            <tbody>
                                                <tr>
                                                    <td class="w-50">
                                                        <div class="invoice_pay_method wsus__dashboard_invoice_left">
                                                            <h5>{{ __('Payment Method') }}</h5>
                                                            <p>{{ __('Payment') }}: {{ $order?->payment_method }}
                                                            </p>
                                                            @if ($order?->transaction_id != 'hand_cash')
                                                                <p>{{ __('Transaction Id') }}:
                                                                    {{ $order?->transaction_id }}</p>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td class="w-50">
                                                        <div
                                                            class="wsus__dashboard_invoice_left wsus__dashboard_invoice_right">
                                                            <p>{{ __('Sub Total') }} : <span
                                                                    class="invoice-amount-box">{{ specific_currency_with_icon($order?->payable_currency, $order?->sub_total) }}</span>
                                                            </p>
                                                            @if ($order->discount)
                                                                <p>{{ __('Discount') }} : <span
                                                                        class="invoice-amount-box">{{ specific_currency_with_icon($order?->payable_currency, $order?->discount) }}
                                                                        (-)</span>
                                                                </p>
                                                            @endif
                                                            <p>{{ __('Tax') }} : <span
                                                                    class="invoice-amount-box">{{ specific_currency_with_icon($order?->payable_currency, $order?->order_tax) }}</span>
                                                            </p>
                                                            <p>{{ __('Delivery Charge') }} : <span
                                                                    class="invoice-amount-box">{{ $order?->delivery_charge > 0 ? specific_currency_with_icon($order?->payable_currency, $order?->delivery_charge) : __('Free') }}</span>
                                                            </p>
                                                            <p>{{ __('Gateway Charge') }} : <span
                                                                    class="invoice-amount-box">{{ specific_currency_with_icon($order?->payable_currency, $order?->gateway_charge) }}</span>
                                                            </p>
                                                            <p><b>{{ __('Total') }}</b> : <span
                                                                    class="invoice-amount-box"><b>{{ specific_currency_with_icon($order?->payable_currency, $order?->paid_amount) }}</b></span>
                                                            </p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a class="btn mt-3" href="{{ route('user.invoice', $order->order_id) }}"
                        target="_blank">
                        <span class="link-effect">
                            <span class="effect-1"><i class="fas fa-print"></i> {{ __('Print') }}</span>
                            <span class="effect-1"><i class="fas fa-print"></i> {{ __('Print') }}</span>
                        </span>
                    </a>
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
