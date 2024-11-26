@extends('backend.layouts.app')
@section('title') Vehicle @endsection
@section('content')

<div class="container d-flex flex-wrap align-items-center align-items-md-start justify-content-end pt-1 pt-md-0 pb-2 pb-md-0 order-md-last">

    <div class="ps-md-5 ps-xl-4">
        <a href="{{route('vehicle.create')}}" class="btn btn-brand btn-brand-secondary text-nowrap"><i class="fa fa-plus me-2"></i> Add Vehicle</a>
    </div>
</div>
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <div class="wiz-card">
            <div class="wiz-card-body">
                <h5 class="wiz-card-title">Vehicle</h5>
                <div class="table-responsive-md">
                    <table class="table table-bordered wiz-table mw-col-width-skip-first">
                        <thead>
                        <tr class="bg-secondary text-white">
                            <th>{{__('pages.sl')}}</th>
                            <th>Vehicle Name</th>
                            <th class="text-center">Vehicle Number</th>

                            <th class="text-center">{{__('pages.action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($vehicles as $key => $vehicle)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$vehicle->name}}</td>
                                <td>{{$vehicle->number}}</td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                        {{-- <a href="{{route('vehicle.edit', [$vehicle->id])}}" class="mx-2 text-brand-primary"><i class="bi bi-pencil"></i></a> --}}
                                        <a href="javascript:void(0);" onclick="$(this).confirmDelete($('#delete-{{$key}}')) " class="mx-2 text-danger"><i class="bi bi-trash3"></i></a>
                                        <form action="{{ route('vehicle.destroy',$vehicle->id) }}" method="post" id="delete-{{$key}}"> @csrf @method('delete') </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>


                <div class="py-3">
                    {{$vehicles->links()}}
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

@endsection
