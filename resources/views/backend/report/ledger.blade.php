@extends('backend.layouts.app')
@section('title') {{__('pages.profit_loss_report')}} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid settings-page">
        <div class="wiz-box mb-3">
            <div class="d-md-none my-2">
                <a href="javascript:void(0)" data-bs-toggle="collapse" data-bs-target="#mwFilterCollapse" class="btn btn-soft-primary">
                    <span class="title-normal m-0">Filter</span>
                    <span><i class="bi bi-funnel"></i></span>
                </a>
            </div>

            <div class="collapse d-md-block" id="mwFilterCollapse">

                        <form action="{{url('report/ledger')}}" method="get">
                            <div class="">
                                <div class="row align-items-center">
                                    <div class="col-md-2">
                                        <div class="form-group text-left">
                                            <input type="text" name="month" data-date-format="yyyy-M"  value="{{Request::get('month')}}"  placeholder="{{__('pages.select_month')}}" id="datepicker" class="form-control" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" name="year" data-date-format="yyyy"  value="{{Request::get('year')}}"  placeholder="{{__('pages.select_year')}}" id="yearPicker" class="form-control" autocomplete="off">
                                    </div>
                                    <div class="col-md-3">
                                        <select name="customer_id" class="form-select select2-basic">
                                            <option value="">All Site</option>
                                            @foreach($customers as $customer)
                                                <option value="{{$customer->id}}" {{Request::get('customer_id') == $customer->id ? 'selected' : ''}}>{{$customer->name}}, {{$customer->phone}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                       <select name="type" id="" class="form-select">
                                        <option value="">Over All</option>
                                        <option value="Income" {{Request::get('type') == 'Income' ? 'selected' : ''}}>Income</option>
                                        <option value="Purchase" {{Request::get('type') == 'Purchase' ? 'selected' : ''}}>Purchase</option>
                                        <option value="Expense" {{Request::get('type') == 'Expense' ? 'selected' : ''}}>Expense</option>

                                       </select>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group pt-2 pt-md-0 text-end">
                                            <button class="btn btn-brand btn-brand-primary">{{__('pages.search')}}</button>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </form>

            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="wiz-card h-auto">
                    <div class="wiz-card-header">
                        <h5 class="wiz-card-title">Ledger</h5>
                    </div>
                    <div class="wiz-card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered wiz-table text-center mw-col-width-skip-first">
                            <thead>
                            <tr class="bg-secondary text-white">
                                <th scope="col" style="width: 60px">{{__('pages.sl')}}</th>
                                <th scope="col">{{__('pages.date')}}</th>
                                <th scope="col">Debit</th>
                                <th scope="col">Credit</th>
                                <th scope="col">Type</th>
                                <th>Remark</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $debit = 0;
                                $credit = 0;
                            @endphp
                            @foreach($ledgers as $key => $ledger)
                                @php
                                    $debit += $ledger['debit'];
                                    $credit += $ledger['credit'];
                                @endphp

                                <tr class="">
                                    <td>{{$key+1}}</td>

                                    <td>@formatdate($ledger['date'])</td>
                                    <td>{{$ledger['debit']??'-'}}</td>
                                    <td>{{$ledger['credit']??'-'}}</td>
                                    <td>{{$ledger['type']??'-'}}</td>
                                    <td>{{$ledger['remark']??'-'}}</td>

                                </tr>
                            @endforeach

                            <tr>
                                    <td colspan="2"><b>Total:</b></td>

                                <td>{{get_option('app_currency')}} <b>{{number_format($debit, 2)}}</b></td>
                                <td>{{get_option('app_currency')}} <b>{{number_format($credit, 2)}}</b></td>
                                {{-- <td>{{get_option('app_currency')}}  <b>{{number_format($total_income - $total_expense, 2)}}</b></td> --}}
                            </tr>
                            </tbody>
                        </table>

                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
    <input type="hidden" value="{{url('/')}}" id="baseUrl">
@endsection
@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('admin/plugin/select2/select2.min.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('admin/plugin/select2/select2-bootstrap/select2-bootstrap-5-theme.css')}}" />


    {{--========== Datepicker ============--}}
    <link rel="stylesheet" type="text/css" href="{{asset('admin/plugin/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}" />
@endsection

@section('js')

    <script src="{{asset('/backend/js/partial/sale-report-statistic.js')}}"></script>


    <script src="{{asset('admin/plugin/select2/select2.min.js')}}"></script>
    <script>
        $('.select2-basic').select2({
            theme: "bootstrap-5",
            width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
            placeholder: $( this ).data( 'placeholder' ),
        });
    </script>

    {{--============== Datepicker ================--}}
    <script src="{{asset('admin/plugin/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
    <script>
        $('.datepicker').datepicker({
            format:'yyyy-mm-dd',
            zIndexOffset: 1198,
        }).on('changeDate', function(e) {
            // when the date is changed
            $(this).datepicker('hide');
        });
    </script>
@endsection
