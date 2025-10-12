@extends('admin.master_layout')
@section('title')
    <title>{{ __('Edit Template') }}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Edit Template') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Settings') => route('admin.settings'),
                __('Email Configuration') => route('admin.email-configuration'),
                __('Edit Template') => '#',
            ]" />

            <div class="section-body">
                <div class="row mt-4">
                    <div class="col">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Edit Template')" />
                                <div>
                                    <x-admin.back-button :href="route('admin.email-configuration')" />
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <th>{{ __('Variable') }}</th>
                                        <th>{{ __('Meaning') }}</th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            @php
                                                $name = '{{user_name}}';
                                            @endphp
                                            <td>{{ $name }}</td>
                                            <td>{{ __('User Name') }}</td>
                                        </tr>
                                        <tr>
                                            @php
                                                $sub_total = '{{sub_total}}';
                                            @endphp
                                            <td>{{ $sub_total }}</td>
                                            <td>{{ __('Sub Total') }}</td>
                                        </tr>
                                        <tr>
                                            @php
                                                $discount = '{{discount}}';
                                            @endphp
                                            <td>{{ $discount }}</td>
                                            <td>{{ __('Discount Amount') }}</td>
                                        </tr>
                                        <tr>
                                            @php
                                                $tax = '{{tax}}';
                                            @endphp
                                            <td>{{ $tax }}</td>
                                            <td>{{ __('Tax') }}</td>
                                        </tr>
                                        <tr>
                                            @php
                                                $delivery_charge = '{{delivery_charge}}';
                                            @endphp
                                            <td>{{ $delivery_charge }}</td>
                                            <td>{{ __('Delivery Charge') }}</td>
                                        </tr>
                                        <tr>
                                            @php
                                                $total_amount = '{{total_amount}}';
                                            @endphp
                                            <td>{{ $total_amount }}</td>
                                            <td>{{ __('Total Amount') }}</td>
                                        </tr>
                                        <tr>
                                            @php
                                                $payment_method = '{{payment_method}}';
                                            @endphp
                                            <td>{{ $payment_method }}</td>
                                            <td>{{ __('Payment Method') }}</td>
                                        </tr>
                                        <tr>
                                            @php
                                                $payment_status = '{{payment_status}}';
                                            @endphp
                                            <td>{{ $payment_status }}</td>
                                            <td>{{ __('Payment Status') }}</td>
                                        </tr>
                                        <tr>
                                            @php
                                                $order_status = '{{order_status}}';
                                            @endphp
                                            <td>{{ $order_status }}</td>
                                            <td>{{ __('Order Status') }}</td>
                                        </tr>
                                        <tr>
                                            @php
                                                $order_date = '{{order_date}}';
                                            @endphp
                                            <td>{{ $order_date }}</td>
                                            <td>{{ __('Order Date') }}</td>
                                        </tr>
                                        <tr>
                                            @php
                                                $order_detail = '{{order_detail}}';
                                            @endphp
                                            <td>{{ $order_detail }}</td>
                                            <td>{{ __('Order Details') }}</td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('admin.update-email-template', $template->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <x-admin.form-input id="subject"  name="subject" label="{{ __('Subject') }}" value="{{ $template->subject }}" required="true"/>
                                    </div>
                                    <div class="form-group">
                                        <x-admin.form-editor id="message" name="message" label="{{ __('Message') }}" value="{!! $template->message !!}" required="true"/>
                                    </div>
                                    <x-admin.update-button :text="__('Update')" />
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>
@endsection
