@extends('admin.master_layout')
@section('title')
    <title>{{ __('Dashboard') }}</title>
@endsection
@use('Carbon\Carbon', 'Carbon')
@section('admin-content')
    <div class="main-content">
        {{-- Show Credentials Setup Alert --}}
        <div class="row position-relative">
            @if (Route::is('admin.dashboard') && ($checkCrentials = checkCrentials()))
                @foreach ($checkCrentials as $checkCrential)
                    @if ($checkCrential->status)
                        <div
                            class="alert alert-danger alert-has-icon alert-dismissible position-absolute w-100 missingCrentialsAlert">
                            <div class="alert-icon"><i class="far fa-lightbulb"></i></div>
                            <div class="alert-body">
                                <div class="alert-title">{{ $checkCrential->message }}</div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

                                {{ $checkCrential->description }} <b><a class="btn btn-sm btn-outline-warning"
                                        href="{{ !empty($checkCrential->route) ? route($checkCrential->route) : url($checkCrential->url) }}">{{ __('Update') }}</a></b>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif
        </div>

        @if ($setting?->is_queable == 'active' && Cache::get('corn_working') !== 'working')
            <div class="alert alert-danger alert-has-icon alert-dismissible show fade">
                <div class="alert-icon"><i class="fas fa-sync"></i></div>
                <div class="alert-body">
                    <div class="alert-title"><a href="{{ route('admin.general-setting') }}" target="_blank"
                            rel="noopener noreferrer">{{ __('Corn Job Is Not Running! Many features will be disabled and face errors') }}</a>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        <section class="section">
            <x-admin.breadcrumb title="{{ __('Dashboard') }}" />
            @if (checkAdminHasPermission('dashboard.view'))
                <div class="section-body">
                    <div class="row">
                        @if ($setting?->is_shop)
                            <!-- Earnings (Monthly) Card Example -->
                            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                                <div class="card card-statistic-1">
                                    <div class="card-icon bg-primary">
                                        <i class="fas fa-shopping-cart"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4>{{ __('Total Order') }}</h4>
                                        </div>
                                        <div class="card-body">
                                            {{ count($total_order) }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @endif



                        @if ($setting->is_shop == 1)
                            <!-- Earnings (Monthly) Card Example -->
                            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                                <div class="card card-statistic-1">
                                    <div class="card-icon bg-success">
                                        <i class="fas fa-dollar-sign"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4>{{ __('Earnings') }} ({{ __('Monthly') }})</h4>
                                        </div>
                                        <div class="card-body">
                                            {{ currency($monthlyEarning) }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Earnings (Monthly) Card Example -->
                            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                                <div class="card card-statistic-1">
                                    <div class="card-icon bg-success">
                                        <i class="fas fa-dollar-sign"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4>{{ __('Earnings') }} ({{ __('Total') }})</h4>
                                        </div>
                                        <div class="card-body">
                                            {{ currency($totalEarning) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-success">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>{{ __('Total Subscriber') }}</h4>
                                    </div>
                                    <div class="card-body">
                                        {{ count($total_newsletter) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($setting?->is_shop)
                        <div class="row">
                            <!-- Area Chart -->
                            <div class="col">
                                <div class="mb-4 shadow card">
                                    <!-- Card Header - Dropdown -->
                                    <div
                                        class="flex-row py-3 card-header d-flex align-items-center justify-content-between">
                                        <h6 class="m-0 font-weight-bold text-primary"> {{ __('Earnings In') }}
                                            {{ request()->has('year') && request()->has('month') ? Carbon::createFromFormat('Y-m', request('year') . '-' . request('month'))->format('F, Y') : date('F, Y') }}
                                        </h6>
                                        <div class="form-inline">
                                            <form method="get" class="on-change-submit d-flex">
                                                <select name="year" id="year" class="form-select w-auto">
                                                    @php
                                                        $currentYear = Carbon::now()->year;
                                                        $currentMonth = Carbon::now()->month;
                                                        $selectYear = request('year') ?? $currentYear;
                                                        $selectMonth = request('month') ?? $currentMonth;
                                                    @endphp
                                                    @for ($i = $oldestYear; $i <= $latestYear; $i++)
                                                        <option value="{{ $i }}" @selected($selectYear == $i)>
                                                            {{ $i }}</option>
                                                    @endfor
                                                </select>
                                                <select name="month" id="month" class="form-select w-auto">
                                                    @php
                                                        for ($month = 1; $month <= 12; $month++) {
                                                            $monthNumber = str_pad($month, 2, '0', STR_PAD_LEFT);
                                                            $monthName = Carbon::createFromFormat('m', $month)->format(
                                                                'M',
                                                            );
                                                            echo '<option value="' .
                                                                $monthNumber .
                                                                '" ' .
                                                                ($selectMonth == $monthNumber ? 'selected' : '') .
                                                                '>' .
                                                                $monthName .
                                                                '</option>';
                                                        }
                                                    @endphp
                                                </select>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- Card Body -->
                                    <div class="card-body">
                                        <div class="chart-area">
                                            <canvas id="myAreaChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="row">
                        @if ($setting?->is_shop)
                            @adminCan('order.management')
                                <div class="col-lg-6 col-md-12 col-12 col-sm-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>{{ __('Recent Orders') }}</h4>
                                            <div class="card-header-action">
                                                <a href="{{ route('admin.orders') }}"
                                                    class="btn btn-primary">{{ __('View All') }}</a>
                                            </div>
                                        </div>
                                        <div class="p-0 card-body">
                                            <div class="table-responsive">
                                                <table class="table mb-0 table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ __('User') }}</th>
                                                            <th>{{ __('Order Id') }}</th>
                                                            <th>{{ __('Payment') }}</th>
                                                            <th>{{ __('Action') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse ($latestOrders as $order)
                                                            <tr>
                                                                <td><a target="_blank"
                                                                    href="{{ route('admin.customer-show', $order->user_id) }}">{{ $order?->user?->name }}</a>
                                                            </td>
                                                            <td>#{{ $order->order_id }}</td>
                                                            <td>
                                                                <div class="badge badge-{{\App\Enums\OrderStatus::getColor($order->payment_status)}}">{{\App\Enums\OrderStatus::getLabel($order->payment_status)}}</div>
                                                            </td>
                                                                <td>
                                                                    <a href="{{ route('admin.order', $order->order_id) }}"
                                                                        class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></a>
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="3">{{ __('No data found') }}</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endadminCan
                        @endif
                        @adminCan('blog.view')
                            <div class="col-lg-6 col-md-12 col-12 col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>{{ __('Latest Posts') }}</h4>
                                        <div class="card-header-action">
                                            <a href="{{ route('admin.blogs.index') }}"
                                                class="btn btn-primary">{{ __('View All') }}</a>
                                        </div>
                                    </div>
                                    <div class="p-0 card-body">
                                        <div class="table-responsive">
                                            <table class="table mb-0 table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('Title') }}</th>
                                                        <th>{{ __('Post Comments') }}</th>
                                                        <th>{{ __('Action') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($latestBlogPosts as $post)
                                                        <tr>
                                                            <td>
                                                                {{ $post?->title }}
                                                                <div class="table-links">
                                                                    <a
                                                                        href="{{ route('blogs', ['category' => $post?->category?->slug]) }}">{{ $post?->category?->title }}</a>
                                                                    <div class="bullet"></div>
                                                                    <a href="{{ route('single.blog', $post?->slug) }}"><i
                                                                            class="fas fa-eye"></i>
                                                                        {{ $post?->views ?? 0 }}</a>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <a href="{{ route('admin.blog-comment.show', $post?->id) }}"
                                                                    target="_blank"
                                                                    rel="noopener noreferrer">{{ $post?->comments_count }}</a>
                                                            </td>
                                                            <td>
                                                                <a href="{{ route('admin.blogs.edit', $post?->id) }}"
                                                                    class="btn btn-sm btn-primary btn-action"
                                                                    data-toggle="tooltip" title="{{ __('Edit') }}"
                                                                    data-original-title="{{ __('Edit') }}"><i
                                                                        class="fas fa-pencil-alt"></i></a>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="3">{{ __('No data found!') }}</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endadminCan
                    </div>
                </div>
            @endif
        </section>
    </div>
@endsection

@push('js')
    @if (checkAdminHasPermission('dashboard.view') && $setting?->is_shop)
        <script src="{{ asset('backend/js/chart.umd.min.js') }}"></script>
        <script>
            (function($) {

                "use strict";

                // Area Chart Example
                $(document).ready(function() {

                    var bData = @json($monthly_data);
                    var jData = JSON.parse(bData);

                    var ctx = document.getElementById("myAreaChart");
                    var myLineChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13",
                                "14", "15", "16", "17", "18", "19", "20", "21", "22", "23", "24", "25",
                                "26", "27", "28", "29", "30", "31"
                            ],
                            datasets: [{
                                label: "{{ __('Earnings') }}",
                                lineTension: 0.3,
                                backgroundColor: "rgba(78, 115, 223, 0.05)",
                                borderColor: "rgba(78, 115, 223, 1)",
                                pointRadius: 3,
                                pointBackgroundColor: "rgba(78, 115, 223, 1)",
                                pointBorderColor: "rgba(78, 115, 223, 1)",
                                pointHoverRadius: 3,
                                pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                                pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                                pointHitRadius: 10,
                                pointBorderWidth: 2,
                                data: jData,
                            }],
                        },
                        options: {
                            maintainAspectRatio: false,
                            layout: {
                                padding: {
                                    left: 10,
                                    right: 25,
                                    top: 25,
                                    bottom: 0
                                }
                            },
                            scales: {
                                xAxes: [{
                                    time: {
                                        unit: 'date'
                                    },
                                    gridLines: {
                                        display: false,
                                        drawBorder: false
                                    },
                                    ticks: {
                                        maxTicksLimit: 7
                                    }
                                }],
                                yAxes: [{
                                    ticks: {
                                        maxTicksLimit: 5,
                                        padding: 10,
                                        // Include a dollar sign in the ticks
                                        callback: function(value, index, values) {
                                            return '{{ session()->get('currency_icon') }}' +
                                                number_format(value);
                                        }
                                    },
                                    gridLines: {
                                        color: "rgb(234, 236, 244)",
                                        zeroLineColor: "rgb(234, 236, 244)",
                                        drawBorder: false,
                                        borderDash: [2],
                                        zeroLineBorderDash: [2]
                                    }
                                }],
                            },
                            legend: {
                                display: false
                            },
                            tooltips: {
                                backgroundColor: "rgb(255,255,255)",
                                bodyFontColor: "#858796",
                                titleMarginBottom: 10,
                                titleFontColor: '#6e707e',
                                titleFontSize: 14,
                                borderColor: '#dddfeb',
                                borderWidth: 1,
                                xPadding: 15,
                                yPadding: 15,
                                displayColors: false,
                                intersect: false,
                                mode: 'index',
                                caretPadding: 10,
                                callbacks: {
                                    label: function(tooltipItem, chart) {
                                        var datasetLabel = chart.datasets[tooltipItem.datasetIndex]
                                            .label || '';
                                        return datasetLabel +
                                            ': {{ session()->get('currency_icon') }}' +
                                            number_format(tooltipItem.yLabel);
                                    }
                                }
                            }
                        }
                    });
                });
            })(jQuery);
        </script>
    @endif
@endpush
