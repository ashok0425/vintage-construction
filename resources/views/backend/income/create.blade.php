@extends('backend.layouts.app')
@section('title') Income  @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">


        <div class="wiz-card">
            <div class="wiz-card-header">
                <h5 class="wiz-card-title mb-0">Add Income</h5>
                <div>
                    <a href="{{route('income.index')}}" class="btn btn-brand btn-brand-secondary btn-sm"><i class="fa fa-list me-2"></i> Income List</a>
                </div>
            </div>
            <div class="wiz-card-body p-4">
                <form action="{{route('income.store')}}" method="post" data-parsley-validate>
                    @csrf

                    <div class="row">
                        <div class="custom-form-group col-md-6">
                            <label for="expense_date" class="custom-label">Income Date <span class="text-danger">*</span></label>
                            <input name="expense_date" value="{{old('expense_date') ? old('expense_date') : \Carbon\Carbon::now()->format('Y-m-d')}}" id="expense_date" type="text" data-date-format="yyyy-mm-dd" class="datepicker form-control" placeholder="{{__('pages.expense_date')}}" required autocomplete="off">
                            @if ($errors->has('expense_date'))
                                <div class="error">{{ $errors->first('expense_date') }}</div>
                            @endif
                        </div>

                        @can('do anything')
                        <div class="custom-form-group col-md-6">
                            <label for="customer_id" class="custom-label">Construction Site <span class="text-danger">*</span></label>
                            <select name="customer_id" id="customer_id" class="form-select select2-basic" required>
                                <option value="">Select Site</option>
                                @foreach($customers as $customer)
                                    <option value="{{$customer->id}}" {{old('customer_id') == $customer->id ? 'selected' : ''}}>{{$customer->site_name}}</option>
                                @endforeach
                            </select>

                            @if ($errors->has('customer_id'))
                                <div class="error mt-1">{{ $errors->first('customer_id') }}</div>
                            @endif
                        </div>
                        @endcan
                        @if (request()->query('other'))
                        <div class="custom-form-group  col-md-6">
                            <label for="vehicle_id" class="custom-label">Select Vehicle <span class="text-danger">*</span></label>
                            <select name="vehicle_id" id="vehicle_id" class="form-select select2-basic" required>
                                <option value="">Vehicle</option>
                                @foreach($vehicles as $vehicle)
                                    <option value="{{$vehicle->id}}" {{old('vehicle') == $vehicle->id ? 'selected' : ''}}>{{$vehicle->name}}{{$vehicle->number}}</option>
                                @endforeach
                            </select>

                            @if ($errors->has('vehicle_id'))
                                <div class="error mt-1">{{ $errors->first('vehicle_id') }}</div>
                            @endif
                        </div>
                        <div class="custom-form-group col-md-6">
                            <label for="other" class="custom-label">Quantity <span class="text-danger">*</span></label>
                            <input type="text" name="other" step=".1" min="0" id="other" value="{{old('other')}}" placeholder="other" class="form-control" aria-describedby="emailHelp" required>
                            @if ($errors->has('other'))
                                <div class="error">{{ $errors->first('other') }}</div>
                            @endif
                        </div>

                        <div class="custom-form-group col-md-6">
                            <label for="unit" class="custom-label">Unit (ltr,hours,trip)<span class="text-danger">*</span></label>
                            <input type="text" name="unit"  min="0" id="unit" value="{{old('unit')}}" placeholder="unit" class="form-control" aria-describedby="emailHelp" required>
                            @if ($errors->has('unit'))
                                <div class="error">{{ $errors->first('unit') }}</div>
                            @endif
                        </div>


                        <div class="custom-form-group col-md-6">
                            <label for="unit_amount" class="custom-label">Unit Amount</label>
                            <input type="number" name="unit_amount" step=".1" min="0" id="unit_amount" value="{{old('unit_amount')}}" onkeyup="calAmt(this.value)" placeholder="Unit Amount" class="form-control" aria-describedby="emailHelp">
                            @if ($errors->has('unit_amount'))
                                <div class="error">{{ $errors->first('unit_amount') }}</div>
                            @endif
                        </div>
                        <div class="custom-form-group col-md-6">
                            <label for="amount" class="custom-label">{{__('pages.amount')}}</label>
                            <input type="number" name="amount" step=".1" min="0" id="amount" value="{{old('amount')}}" placeholder="{{__('pages.amount')}}" class="form-control" aria-describedby="emailHelp">
                            @if ($errors->has('amount'))
                                <div class="error">{{ $errors->first('amount') }}</div>
                            @endif
                        </div>

                        <div class="custom-form-group col-md-6">
                            <label for="pickup_loc" class="custom-label">Pickup Location</label>
                            <input type="text" name="pickup_loc"  min="0" id="pickup_loc" value="{{old('pickup_loc')}}" placeholder="Pickup Location" class="form-control">
                            @if ($errors->has('pickup_loc'))
                                <div class="error">{{ $errors->first('pickup_loc') }}</div>
                            @endif
                        </div>


                        <div class="custom-form-group col-md-6">
                            <label for="drop_loc" class="custom-label">Drop Location</label>
                            <input type="text" name="drop_loc"  min="0" id="drop_loc" value="{{old('drop_loc')}}" placeholder="Pickup Location" class="form-control">
                            @if ($errors->has('drop_loc'))
                                <div class="error">{{ $errors->first('drop_loc') }}</div>
                            @endif
                        </div>


                        @else
                        <div class="custom-form-group col-md-6">
                            <label for="expense_category_id" class="custom-label">Income Category <span class="text-danger">*</span></label>
                            <select name="expense_category_id" id="expense_category_id" class="form-select select2-basic">
                                <option value="">{{__('pages.select_category')}}</option>
                                @foreach($expense_categories as $expense_category)
                                    <option value="{{$expense_category->id}}" {{old('expense_category_id') == $expense_category->id ? 'selected' : ''}}>{{$expense_category->name}}</option>
                                @endforeach
                            </select>

                            @if ($errors->has('expense_category_id'))
                                <div class="error mt-1">{{ $errors->first('expense_category_id') }}</div>
                            @endif
                        </div>


                        <div class="custom-form-group col-md-6">
                            <label for="amount" class="custom-label">{{__('pages.amount')}} <span class="text-danger">*</span></label>
                            <input type="number" name="amount" step=".1" min="0" id="amount" value="{{old('amount')}}" placeholder="{{__('pages.amount')}}" class="form-control" aria-describedby="emailHelp" required>
                            @if ($errors->has('amount'))
                                <div class="error">{{ $errors->first('amount') }}</div>
                            @endif
                        </div>
                        @endif




                        <div class="custom-form-group col-md-6 mb-4">
                            <label for="note" class="custom-label">{{__('pages.note')}}</label>
                            <textarea name="note" placeholder="Short Note" class="form-control">{{old('note')}}</textarea>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-brand btn-brand-primary">{{__('pages.save')}}</button>
                        </div>
                    </div>

                </form>
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

        function calAmt(unit) {
    let qty = document.querySelector('#other').value; // Get the quantity
    let total = parseFloat(unit) * parseFloat(qty); // Calculate the total amount
    if (!isNaN(total)) {
        document.querySelector('#amount').value = total.toFixed(2); // Update the amount field
    }
}
    </script>
@endsection

