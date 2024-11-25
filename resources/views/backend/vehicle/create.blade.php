@extends('backend.layouts.app')
@section('title') Vehicle  @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">


        <div class="wiz-card">
            <div class="wiz-card-header">
                <h5 class="wiz-card-title mb-0">Create Vehicle </h5>
                <div>
                    <a href="{{route('vehicle.index')}}" class="btn btn-brand btn-brand-secondary btn-sm"><i class="fa fa-list me-2"></i> vehicle list</a>
                </div>
            </div>
            <div class="wiz-card-body p-4">
                <form action="{{route('vehicle.store')}}" method="post">
                    @csrf

                    <div>
                        <div class="custom-form-group">
                            <label for="name" class="custom-label">Name<span class="text-danger">*</span></label>
                            <input name="name" value="{{old('name') }}" id="name" type="text" class=" form-control" placeholder="Vehicle Name" required autocomplete="off">
                            @if ($errors->has('name'))
                                <div class="error">{{ $errors->first('name') }}</div>
                            @endif
                        </div>


                        <div class="custom-form-group">
                            <label for="number" class="custom-label">Number<span class="text-danger">*</span></label>
                            <input name="number" value="{{old('number') }}" id="name" type="text" class=" form-control" placeholder="Vehicle Number" required autocomplete="off">
                            @if ($errors->has('number'))
                                <div class="error">{{ $errors->first('number') }}</div>
                            @endif
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
    </script>
@endsection

