<form action="{{route('expense.index')}}" method="get">

    <div class="row g-3">
        <div class="col-md-3">
            <select name="customer_id" class="form-select select2-basic">
                <option value="">All Site</option>
                @foreach($customers as $customer)
                    <option value="{{$customer->id}}" {{Request::get('customer_id') == $customer->id ? 'selected' : ''}}>{{$customer->site_name}} </option>
                @endforeach
            </select>
        </div>

        @if(request()->query('other'))
        <input type="hidden" value="1" name="other">
        <div class="col-sm-6 col-lg-4 col-xl">
            <div class="form-group">
                <select name="vehicle_id" class="form-control select2-basic">
                    <option value="">All Vehicle</option>
                    @foreach($vehicles as $vehicle)
                        <option value="{{$vehicle->id}}" {{Request::get('vehicle_id') == $vehicle->id ? 'selected' : ''}}>{{$vehicle->name}} ,{{$vehicle->number}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        @else
        <div class="col-sm-6 col-lg-4 col-xl">
            <div class="form-group">
                <select name="expense_category_id" class="form-control select2-basic">
                    <option value="">{{__('pages.all_category')}}</option>
                    @foreach($expense_categories as $expense_category)
                        <option value="{{$expense_category->id}}">{{$expense_category->name}} </option>
                    @endforeach
                </select>
            </div>
        </div>
        @endif


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
