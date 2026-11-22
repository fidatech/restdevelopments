@extends('admin.layouts.master')

@section('title', 'Dashboard')

@section('page-style')
    <link rel="stylesheet" href="{{ asset('admin/app-assets/vendors/css/charts/chartist.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/app-assets/css/pages/dashboard-analytics.css') }}">
@endsection

@section('content')
    <div class="content-wrapper-before"></div>
    <div class="content-header row"></div>

    <div class="container-fluid">
    <div class="row g-3">
      <div class="col-md-3"><div class="card shadow-sm p-3">Total Projects</div></div>
      <div class="col-md-3"><div class="card shadow-sm p-3">Total Properties</div></div>
      <div class="col-md-3"><div class="card shadow-sm p-3">Leads</div></div>
      <div class="col-md-3"><div class="card shadow-sm p-3">Published Posts</div></div>
    </div>
  </div>
    <!--/ eCommerce & Project statistic -->
    {{-- STATISTICS WITH GRADIENT BACKGROUND COLOR --}}
    {{-- <div class="row match-height">
        @php
            $stats = [
                ['title' => 'Total Orders', 'value' => '1,245', 'desc' => 'This Month', 'class' => 'primary'],
                ['title' => 'Total Clients', 'value' => '230', 'desc' => 'Afghanistan', 'class' => 'pink'],
                ['title' => 'Trucks In Transit', 'value' => '18', 'desc' => 'Across Border', 'class' => 'purple'],
                ['title' => 'Pending Payments', 'value' => '؋24,800', 'desc' => 'Unpaid Clients', 'class' => 'warning'],
                ['title' => 'Suppliers', 'value' => '15', 'desc' => 'Pakistan', 'class' => 'purple'],
                ['title' => 'Total Purchases', 'value' => '؋1,200,000', 'desc' => 'Lifetime', 'class' => 'warning'],
                ['title' => 'Total Sales', 'value' => '؋9,000,000', 'desc' => 'Lifetime', 'class' => 'info'],
                ['title' => 'Total Revenue', 'value' => '؋298,560', 'desc' => 'Delivered', 'class' => 'danger'],
            ];
        @endphp
        @foreach ($stats as $stat)
            <div class="col-xl-3 col-md-4 col-sm-6">
                <div class="card bg-gradient-directional-{{ $stat['class'] }}">
                    <div class="card-content text-white">
                        <div class="card-body">
                            <h3 class="card-title text-white">{{ $stat['title'] }}</h3>
                            <h4 class="card-value text-white">{{ $stat['value'] }}</h4>
                            <p class="card-desc">{{ $stat['desc'] }}</p>
                        </div>
                    </div>
                    <div class="card-shadow"></div>
                </div>
            </div>
        @endforeach
    </div> --}}

    <!-- Cement Supply Project Statistics -->
    {{-- <section id="cement-project-stats">
        <div class="row">
            <div class="col-xl-4 col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Peshawar Cement Supply</h4>
                        <div class="heading-elements">
                            <ul class="list-inline d-block mb-0">
                                <li>
                                    <a class="btn btn-sm btn-info box-shadow-3 round btn-min-width pull-right" href="#" target="_blank">In Progress</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content show collapse">
                        <div class="card-body pb-1 pt-1">
                            <h6 class="text-bold-600"> Deliveries Completed:
                                <span>4/10</span>
                            </h6>
                            <div class="progress progress-sm box-shadow-2 mb-0 mt-1">
                                <div class="progress-bar bg-gradient-x-info" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="media d-flex">
                                <div class="align-self-center">
                                    <h6 class="text-bold-600 mt-2"> Contractor:
                                        <span class="info">Wahid Builders</span>
                                    </h6>
                                    <h6 class="text-bold-600 mt-1"> Deadline:
                                        <span class="blue-grey">5th June, 2025</span>
                                    </h6>
                                </div>
                                <div class="media-body mt-2 text-right">
                                    <ul class="list-unstyled users-list">
                                        <li class="avatar avatar-sm pull-up" data-toggle="tooltip" data-popup="tooltip-custom" data-original-title="Driver A">
                                            <img class="media-object rounded-circle" src="app-assets/images/portrait/small/avatar-s-19.png" alt="Avatar">
                                        </li>
                                        <li class="avatar avatar-sm pull-up" data-toggle="tooltip" data-popup="tooltip-custom" data-original-title="Driver B">
                                            <img class="media-object rounded-circle" src="app-assets/images/portrait/small/avatar-s-18.png" alt="Avatar">
                                        </li>
                                        <li class="avatar avatar-sm pull-up" data-toggle="tooltip" data-popup="tooltip-custom" data-original-title="Driver C">
                                            <img class="media-object rounded-circle" src="app-assets/images/portrait/small/avatar-s-17.png" alt="Avatar">
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Bannu Cement Supply</h4>
                        <div class="heading-elements">
                            <ul class="list-inline d-block mb-0">
                                <li>
                                    <a class="btn btn-sm btn-warning box-shadow-3 round btn-min-width pull-right" href="#" target="_blank">Completed</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content show collapse">
                        <div class="card-body pb-1 pt-1">
                            <h6 class="text-bold-600"> Deliveries Completed:
                                <span>10/10</span>
                            </h6>
                            <div class="progress progress-sm box-shadow-2 mb-0 mt-1">
                                <div class="progress-bar bg-gradient-x-warning" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="media d-flex">
                                <div class="align-self-center">
                                    <h6 class="text-bold-600 mt-2"> Contractor:
                                        <span class="warning">Safi Construction</span>
                                    </h6>
                                    <h6 class="text-bold-600 mt-1"> Deadline:
                                        <span class="blue-grey">11th May, 2025</span>
                                    </h6>
                                </div>
                                <div class="media-body mt-2 text-right">
                                    <ul class="list-unstyled users-list">
                                        <li class="avatar avatar-sm pull-up" data-toggle="tooltip" data-popup="tooltip-custom" data-original-title="Driver D">
                                            <img class="media-object rounded-circle" src="app-assets/images/portrait/small/avatar-s-11.png" alt="Avatar">
                                        </li>
                                        <li class="avatar avatar-sm pull-up" data-toggle="tooltip" data-popup="tooltip-custom" data-original-title="Driver E">
                                            <img class="media-object rounded-circle" src="app-assets/images/portrait/small/avatar-s-12.png" alt="Avatar">
                                        </li>
                                        <li class="avatar avatar-sm pull-up" data-toggle="tooltip" data-popup="tooltip-custom" data-original-title="Driver F">
                                            <img class="media-object rounded-circle" src="app-assets/images/portrait/small/avatar-s-13.png" alt="Avatar">
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-12">
                <div class="card">
                    <div class="card-header bg-hexagons">
                        <h4 class="card-title">Delivery Statistics</h4>
                        <div class="heading-elements">
                            <ul class="list-inline d-block mb-0">
                                <li>
                                    <a class="btn btn-sm btn-danger danger box-shadow-3 round btn-min-width pull-right" href="#" target="_blank">
                                        <span class="white">Report</span>
                                        <i class="ft-bar-chart white pl-1"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content show bg-hexagons collapse">
                        <div class="card-body pb-1 pt-1">
                            <div class="media d-flex">
                                <div class="align-self-center width-100">
                                    <div id="statistics-donut-chart" class="height-100 donutShadow"></div>
                                </div>
                                <div class="media-body mt-2 text-right">
                                    <h3 class="font-large-2 blue-grey lighten-1">35,656</h3>
                                    <h6 class="mt-1">
                                        <span class="text-muted">deliveries completed in the
                                            <a href="#" class="darken-2">last year.</a>
                                        </span>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}


    {{-- Charts Section --}}
    {{-- <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Project Revenue</h4>
                </div>
                <div class="card-body">
                    <div id="project-revenue-chart" class="height-300"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">New Purchases Trend</h4>
                </div>
                <div class="card-body">
                    <div id="new-Purchases-chart" class="height-300"></div>
                </div>
            </div>
        </div>
    </div> --}}
@endsection
@section('page-script')
    <script src="{{ asset('admin/app-assets/vendors/js/charts/chartist.min.js') }}"></script>
    <script>
        new Chartist.Line('#project-revenue-chart', {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
            series: [
                [10000, 12000, 18000, 20000, 25000]
            ]
        }, {
            fullWidth: true,
            chartPadding: {
                right: 40
            },
            height: '300px'
        });

        new Chartist.Line('#new-Purchases-chart', {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
            series: [
                [20, 25, 40, 50, 60]
            ]
        }, {
            fullWidth: true,
            chartPadding: {
                right: 40
            },
            height: '300px'
        });
    </script>
@endsection
