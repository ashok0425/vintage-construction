@extends('backend.layouts.app')
@section('title') {{__('pages.dashboard')}} @endsection
@section('content')
    <div class="container-fluid">
        {{--========= Summary =============--}}
        <div class="mb-4">
            <div class="row g-4">

                <div class="col-xl-6 col-md-6">
                    <div class="summary-card">
                        <div class="me-3">
                            <h6 class="summary-card-title">{{__('pages.purchase_of_month')}}</h6>
                            <h3 class="summary-card-value" id="purchase_of_this_month"><img src="{{asset('backend/img/loading.gif')}}" class="img-responsive" height="20"></h3>
                        </div>
                        <div>
                            <span class="summary-card-icon btn-soft-warning"><i class="bi bi-credit-card"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-md-6">
                    <div class="summary-card">
                        <div class="me-3">
                            <h6 class="summary-card-title">{{__('pages.total_purchase_amount')}}</h6>
                            <h3 class="summary-card-value" id="total_purchase"><img src="{{asset('backend/img/loading.gif')}}" class="img-responsive" height="20"></h3>
                        </div>
                        <div>
                            <span class="summary-card-icon btn-soft-danger"><i class="bi bi-basket"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Row -->
        {{-- <div class="row g-4">
            <!-- Area Chart -->
            <div class="col-md-12 col-12">
                <div class="wiz-card">
                    <div class="wiz-card-header align-items-center">
                        <h5 class="wiz-card-title">Purchase</h5>
                        <div>
                            <select class="form-select form-select-sm small text-brand-dark">
                                <option>This Month</option>
                                <option>This Year</option>
                            </select>
                        </div>
                    </div>
                    <div class="wiz-card-body">
                        <div class="ratio ratio-16x9">
                            <div>
                                <canvas id="myAreaChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div> --}}

    </div>
    <!-- /.container-fluid -->
@endsection
@section('js')
    <!-- Page level plugins -->
    <script src="{{asset('/backend/vendor/chart.js/Chart.min.js')}}"></script>
    <script src="{{asset('/backend/js/demo/chart-area-demo.js')}}"></script>
    <script src="{{asset('/backend/js/partial/dashboard.js')}}"></script>
@endsection

