@extends('backend.layouts.app')
@section('title') {{__('pages.customer')}} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <div class="wiz-card">
            <!-- Card Header - Dropdown -->
            <div class="wiz-card-header">
                <h6 class="wiz-card-title">Investments</h6>
                <div>
                    <a href="{{route('investments.create')}}" class="btn btn-brand btn-brand-secondary btn-sm"><i class="fa fa-plus me-1"></i> Add Investment</a>
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
                            <th class="text-center">Investor Name</th>
                            <th class="text-center">Amount</th>
                            <th class="text-center">Date</th>
                            <th class="text-center">Remark</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($investments as $key => $investment)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td class="text-center">{{$investment->customer->site_name}}</td>
                                <td class="text-center">{{$investment->user->name}}</td>
                                <td class="text-center">{{$investment->amount}}</td>
                                <td class="text-center">{{$investment->investment_date}}</td>
                                <td class="text-center">{{$investment->remark}}</td>

                                {{-- <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                        <a href="{{route('customer.edit', [$customer->id])}}" class="mx-2 text-brand-primary"><i class="bi bi-pencil"></i></a>
                                        <a href="{{route('customer.show', [$customer->id])}}" class="mx-2"><i class="bi bi-eye"></i></a>
                                        <a href="javascript:void(0);" onclick="$(this).confirmDelete($('#delete-{{$key}}')) " class="mx-2 text-danger"><i class="bi bi-trash3"></i></a>
                                        <form action="{{ route('customer.destroy',$customer->id) }}" method="post" id="delete-{{$key}}"> @csrf @method('delete') </form>
                                    </div>
                                </td> --}}
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="py-3">
                        {{$investments->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection

@section('js')

@endsection

