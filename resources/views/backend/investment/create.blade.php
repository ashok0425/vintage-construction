@extends('backend.layouts.app')
@section('title') {{__('pages.customer')}}  @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="wiz-card wiz-card-single">
                    <!-- Card Header - Dropdown -->
                    <div class="wiz-card-header">
                        <h6 class="wiz-card-title">Create Investment</h6>
                        <div>
                            <a href="{{route('investments.index')}}" class="btn btn-brand btn-brand-secondary btn-sm"> <i class="fa fa-list me-1"></i> {{__('pages.all_customer')}}</a>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="wiz-card-body">
                        <form action="{{route('investments.store')}}" method="post" enctype="multipart/form-data" data-parsley-validate>
                            @csrf

                            <div class="row g-3">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="user_id" class="custom-label">Select Investor<span class="text-danger">*</span></label>
                                        <select name="user_id" id="user_id" class="form-select form-control">
                                            <option value="">select user</option>
                                            @foreach (App\User::all() as $user)
                                            <option value="{{$user->id}}">{{$user->name}}</option>
                                            @endforeach
                                        </select>

                                        @if ($errors->has('user_id'))
                                            <div class="error">{{ $errors->first('user_id') }}</div>
                                        @endif
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="customer_id" class="custom-label">Select Customer<span class="text-danger">*</span></label>
                                        <select name="customer_id" id="customer_id" class="form-select form-control">
                                            <option value="">select Site</option>
                                            @foreach (App\Models\Customer::all() as $customer)
                                            <option value="{{$customer->id}}">{{$customer->site_name}}</option>
                                            @endforeach
                                        </select>

                                        @if ($errors->has('customer_id'))
                                            <div class="error">{{ $errors->first('customer_id') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="amount" class="custom-label">Amount <span class="text-danger">*</span></label>
                                        <input type="number" name="amount" id="amount" value="{{old('amount')}}" placeholder="Enter Amount" class="form-control form-control-lg" aria-describedby="emailHelp">
                                        @if ($errors->has('amount'))
                                            <div class="error">{{ $errors->first('amount') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="investment_date" class="custom-label">Date <span class="text-danger">*</span></label>
                                        <input type="date" name="investment_date" id="investment_date" value="{{old('investment_date')}}" placeholder="Enter Date" class="form-control form-control-lg" aria-describedby="emailHelp">
                                        @if ($errors->has('investment_date'))
                                            <div class="error">{{ $errors->first('investment_date') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="remark" class="custom-label">Remark</label>
                                        <input type="remark" name="remark" id="remark" value="{{old('remark')}}" placeholder="Enter remark" class="form-control form-control-lg" aria-describedby="emailHelp">
                                        @if ($errors->has('remark'))
                                            <div class="error">{{ $errors->first('remark') }}</div>
                                        @endif
                                    </div>
                                </div>


                            </div>

                            <div class="d-flex justify-content-end py-3">
                                <button type="submit" class="btn btn-brand btn-brand-primary">{{__('pages.save')}}</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection

@section('js')
    <script src="{{asset('/backend/js/custom.js')}}"></script>
@endsection

