@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard - Analytics')

@section('vendor-style')
@vite('resources/assets/vendor/libs/apex-charts/apex-charts.scss')
@endsection

@section('vendor-script')
@vite('resources/assets/vendor/libs/apex-charts/apexcharts.js')
@endsection

@section('page-script')
@vite('resources/assets/js/dashboards-analytics.js')
@endsection

@section('content')
<div class="row">
    <div class="col-xxl-4 col-lg-12 col-md-4 order-0">
        <div class="row">
            <div class="col-lg-6 col-md-12 col-6 mb-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between mb-4">
                            <div class="avatar flex-shrink-0">
                                <img src="{{ asset('assets/img/icons/unicons/users.png') }}" alt="users" class="rounded" />
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="cardOpt6" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="icon-base bx bx-dots-vertical-rounded text-body-secondary"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt6">
                                    <a class="dropdown-item" href="javascript:void(0);">View More</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                </div>
                            </div>
                        </div>
                        <p class="mb-1">Total Users</p>
                        <h4 class="card-title mb-3">{{ '4'}} </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection