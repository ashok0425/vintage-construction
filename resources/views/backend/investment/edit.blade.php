@extends('backend.layouts.app')
@section('title') {{__('pages.customer')}} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="wiz-card wiz-card-single">
                    <!-- Card Header - Dropdown -->
                    <div class="wiz-card-header">
                        <h6 class="wiz-card-title">{{__('pages.update_customer')}}</h6>
                        <div>
                            <a href="{{route('customer.index')}}" class="btn btn-brand btn-brand-secondary btn-sm"> <i class="fa fa-list me-1"></i> All site</a>
                        </div>
                    </div>
                    <!-- Card Body -->
                    <div class="wiz-card-body">

                        <form action="{{route('customer.update', [$customer->id])}}" method="post" enctype="multipart/form-data" data-parsley-validate>
                            @csrf
                            @method('patch')

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="custom-label">Site Name <span class="text-danger">*</span></label>
                                        <input type="text" name="site_name" id="site_name" value="{{old('site_name',$customer->site_name)}}" placeholder="Site Name" class="form-control form-control-lg" aria-describedby="emailHelp" required>
                                        @if ($errors->has('site_name'))
                                            <div class="error">{{ $errors->first('site_name') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="custom-label">{{__('pages.name')}} <span class="text-danger">*</span></label>
                                        <input type="text" name="name" id="name" value="{{$customer->name}}" required placeholder="{{__('pages.customer_name')}}" class="form-control form-control-lg" aria-describedby="emailHelp" required>
                                        @if ($errors->has('name'))
                                            <div class="error">{{ $errors->first('name') }}</div>
                                        @endif
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone" class="custom-label">{{__('pages.phone_number')}} <span class="text-danger">*</span></label>
                                        <input type="text" name="phone" id="phone" value="{{$customer->phone}}" placeholder="{{__('pages.phone_number')}}" class="form-control form-control-lg" aria-describedby="emailHelp">
                                        @if ($errors->has('phone'))
                                            <div class="error">{{ $errors->first('phone') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email" class="custom-label">{{__('pages.email')}}</label>
                                        <input type="email" name="email" id="email" value="{{$customer->email}}" placeholder="{{__('pages.email')}}" class="form-control form-control-lg" aria-describedby="emailHelp">
                                        @if ($errors->has('email'))
                                            <div class="error">{{ $errors->first('email') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address" class="custom-label">{{__('pages.email')}}</label>
                                        <input type="text" name="address" id="address" value="{{$customer->address}}" placeholder="{{__('pages.address')}}" class="form-control form-control-lg" aria-describedby="emailHelp">
                                        @if ($errors->has('address'))
                                            <div class="error">{{ $errors->first('address') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-7 col-sm-4 col-lg-3 col-xl-2 my-4">
                                    <div class="ratio ratio-1x1">
                                        <label class="upload-with-preview">
                                            <input type="file" name="photo" id="photo" accept="image/*" class="upload-img-input">
                                            @if($customer->photo)
                                                <img src="{{asset($customer->photo)}}" class="preview-image">
                                            @else
                                                <img src="" class="preview-image">
                                            @endif
                                            <div class="preview-label-text">
                                                <span><i class="bi bi-cloud-arrow-up-fill text-brand-primary fa-2x lh-sm"></i></span>
                                                <span>{{__('pages.photo')}}</span>
                                                <small class="extra-small text-light-muted">Drag & Drop Image Here</small>
                                                <span class="upload-btn">Browse</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end py-3">
                                <button type="submit" class="btn btn-brand btn-brand-primary">{{__('pages.update')}}</button>
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

