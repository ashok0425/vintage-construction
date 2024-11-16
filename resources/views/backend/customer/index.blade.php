@extends('backend.layouts.app')
@section('title') {{__('pages.customer')}} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <div class="wiz-card">
            <!-- Card Header - Dropdown -->
            <div class="wiz-card-header">
                <h6 class="wiz-card-title">Construction Site</h6>
                <div>
                    <a href="{{route('customer.create')}}" class="btn btn-brand btn-brand-secondary btn-sm"><i class="fa fa-plus me-1"></i> Add Site</a>
                </div>
            </div>
            <!-- Card Body -->
            <div class="wiz-card-body">
                <div class="table-responsive-lg">
                    <table class="table table-bordered text-center wiz-table mw-col-width-skip-first">
                        <thead>
                        <tr class="bg-secondary text-white">
                            <th>{{__('pages.sl')}}</th>
                            <th class="text-center">Site Name</th>
                            <th class="text-center">{{__('pages.name')}}</th>
                            <th class="text-center">{{__('pages.phone_number')}}</th>
                            <th class="text-center">{{__('pages.email')}}</th>
                            <th class="text-center">{{__('pages.action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($customers as $key => $customer)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td class="text-center">{{$customer->site_name}}</td>
                                <td class="text-center">{{$customer->name}}</td>
                                <td class="text-center">{{$customer->phone}}</td>
                                <td class="text-center">{{$customer->email}}</td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                        <a href="{{route('customer.edit', [$customer->id])}}" class="mx-2 text-brand-primary"><i class="bi bi-pencil"></i></a>
                                        <a href="{{route('customer.show', [$customer->id])}}" class="mx-2"><i class="bi bi-eye"></i></a>
                                        <a href="javascript:void(0);" onclick="$(this).confirmDelete($('#delete-{{$key}}')) " class="mx-2 text-danger"><i class="bi bi-trash3"></i></a>
                                        <form action="{{ route('customer.destroy',$customer->id) }}" method="post" id="delete-{{$key}}"> @csrf @method('delete') </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="py-3">
                        {{$customers->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection

@section('js')

@endsection

