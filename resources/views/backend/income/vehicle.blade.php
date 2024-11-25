@extends('backend.layouts.app')
@section('title')Vehicle Income @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <div class="wiz-box mb-4">
            <div class="d-md-flex justify-content-between">
                <div class="d-flex flex-wrap align-items-center align-items-md-start justify-content-between pt-1 pt-md-0 pb-2 pb-md-0 order-md-last">
                    <a href="javascript:void(0)" data-bs-toggle="collapse" data-bs-target="#mwFilterCollapse" class="btn btn-soft-primary d-md-none">
                        <span class="title-normal m-0">Filter</span>
                        <span class="ps-3"><i class="bi bi-funnel"></i></span>
                    </a>
                    <div class="ps-md-5 ps-xl-4">
                        <a href="{{route('income.create',['isvehicle'=>1])}}" class="btn btn-brand btn-brand-secondary text-nowrap"><i class="fa fa-plus me-2"></i> Add Income</a>
                    </div>
                </div>

                <div class="flex-grow-1">
                    <div class="collapse d-md-block" id="mwFilterCollapse">
                        <form action="{{route('income.index')}}" method="get">
                            <input type="hidden" name="isvehicle"   value="1">
                            <div class="row g-3">
                                @can('do anything')
                                <div class="col-md-3">
                                    <select name="customer_id" class="form-select select2-basic">
                                        <option value="">All Site</option>
                                        @foreach($customers as $customer)
                                            <option value="{{$customer->id}}" {{Request::get('customer_id') == $customer->id ? 'selected' : ''}}>{{$customer->site_name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                                @endcan

                                <div class="col-md-3">
                                    <select name="customer_id" class="form-select select2-basic">
                                        <option value="">All Vehicle</option>
                                        @foreach($vehicles as $vehicle)
                                            <option value="{{$vehicle->id}}" {{Request::get('vehicle') == $vehicle->id ? 'selected' : ''}}>{{$vehicle->name}} {{$vehicle->number}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-sm-6 col-lg-4 col-xl">
                                    <div class="form-group">
                                        <input type="text" name="start_date" value="{{Request::get('start_date')}}" data-date-format="yyyy-mm-dd" class="datepicker form-control" placeholder="{{__('pages.start_date')}}" autocomplete="off">
                                    </div>
                                </div>

                                <div class="col-sm-6 col-lg-4 col-xl">
                                    <div class="form-group">
                                        <input type="text" name="end_date" value="{{Request::get('end_date')}}" data-date-format="yyyy-mm-dd" class="datepicker form-control" placeholder="{{__('pages.end_date')}}" autocomplete="off">
                                    </div>
                                </div>

                                <div class="col-sm-6 col-lg-4 col-xl">
                                    <div class="form-group">
                                        <button class="btn btn-brand-primary btn-brand w-100">{{__('pages.search')}}</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>


        <div class="wiz-card">
            <div class="wiz-card-body">
                <h5 class="wiz-card-title">Income</h5>
                <div class="table-responsive-md">
                    <table class="table table-bordered wiz-table mw-col-width-skip-first">
                        <thead>
                        <tr class="bg-secondary text-white">
                            <th>{{__('pages.sl')}}</th>
                            <th>Site Name</th>
                            <th class="text-center">Income Date</th>
                            <th>Vehicle</th>
                            <th class="text-center">{{__('pages.amount')}}</th>
                            <th class="text-center">{{__('pages.action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($expenses as $key => $expense)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$expense->customer->site_name}}</td>
                                <td class="text-center">@formatdate($expense->expense_date)</td>
                                <td class="text-center"> {{$expense->vehicle ? $expense->vehicle->name.'/'.$expense->vehicle->number  : '--'}} </td>
                                <td class="text-center"> {{get_option('app_currency')}}{{number_format($expense->amount, 2)}} </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                        <a href="{{route('income.edit', [$expense->id])}}" class="mx-2 text-brand-primary"><i class="bi bi-pencil"></i></a>
                                        <a href="javascript:void(0)" class="mx-2 show-expense-details" data-bs-toggle="offcanvas" data-bs-target="#expenseDetails{{$expense->id}}" data-expense-id="{{$expense->id}}"><i class="bi bi-eye"></i></a>

                                        <div class="offcanvas offcanvas-end" tabindex="-1" id="expenseDetails{{$expense->id}}">
                                            <div class="offcanvas-header align-items-center border-bottom">
                                                <h5 class="wiz-card-title mb-0">{{__('pages.expense')}}</h5>
                                                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                            </div>
                                            <div class="offcanvas-body">
                                                <table class="table table-borderless wiz-table">
                                                    <tr>
                                                        <td class="text-start ps-0">{{__('pages.expense_id')}}:</td>
                                                        <td class="text-end">{{$expense->expense_id}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start ps-0">{{__('pages.amount')}}:</td>
                                                        <td class="text-end"><b>{{get_option('app_currency')}} {{number_format($expense->amount, 2)}}</b></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start ps-0">{{__('pages.expense_date')}}:</td>
                                                        <td class="text-end">@formatdate($expense->expense_date)</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start ps-0">{{__('pages.category')}}:</td>
                                                        <td class="text-end">{{$expense->expenseCategory ? $expense->expenseCategory->name : '--'}}</td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start ps-0">{{__('pages.note')}}:</td>
                                                        <td class="text-end">{{$expense->note}}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>

                                        <a href="javascript:void(0);" onclick="$(this).confirmDelete($('#delete-{{$key}}')) " class="mx-2 text-danger"><i class="bi bi-trash3"></i></a>
                                        <form action="{{ route('expense.destroy',$expense->id) }}" method="post" id="delete-{{$key}}"> @csrf @method('delete') </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>


                <div class="py-3">
                    {{$expenses->links()}}
                </div>
            </div>
        </div>


    </div>
    <!-- /.container-fluid -->
@endsection


@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('admin/plugin/select2/select2.min.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('admin/plugin/select2/select2-bootstrap/select2-bootstrap-5-theme.css')}}" />


    {{--========== Datepicker ============--}}
    <link rel="stylesheet" type="text/css" href="{{asset('admin/plugin/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}" />
@endsection
@section('js')
    <script src="{{asset('/backend/js/custom.js')}}"></script>

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
