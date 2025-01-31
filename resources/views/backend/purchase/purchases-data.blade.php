<div class="table-responsive-lg">
    <table class="table table-bordered text-center wiz-table mw-col-width-skip-first">
        <thead>
        <tr class="bg-secondary text-white">
            <th>{{__('pages.sl')}}</th>
            <th>{{__('pages.invoice_id')}}</th>
            <th>{{__('pages.date')}}</th>
            <th>{{__('pages.supplier')}}</th>
            <th>{{__('pages.total_amount')}}</th>
            <th>{{__('pages.paid_amount')}}</th>
            <th>{{__('pages.due_amount')}}</th>
            <th>Remark</th>
            <th width="8%">{{__('pages.action')}}</th>
        </tr>
        </thead>
        <tbody>
            @php
                $total_amount=0;
                $paid_amount=0;
                $due_amount=0;
            @endphp
        @forelse($purchases as $key => $purchase)
        @php
            $total_amount+=$purchase->total_amount;
            $paid_amount+=$purchase->paid_amount;
            $due_amount+=$purchase->due_amount;
        @endphp
            <tr>
                <td>{{$key+1}}</td>
                <td>{{$purchase->invoice_id}}</td>
                <td>{{$purchase->purchase_date->format(get_option('app_date_format'))}}</td>

                <td>{{$purchase->supplier ? $purchase->supplier->company_name : ''}}</td>
                <td> {{get_option('app_currency')}}{{number_format($purchase->total_amount, 2)}} </td>
                <td> {{get_option('app_currency')}}{{number_format($purchase->paid_amount, 2)}} </td>
                <td> {{get_option('app_currency')}}{{number_format($purchase->due_amount, 2)}} </td>
                <td>
                    @foreach ($purchase->purchaseProducts as $purchaseProduct)
                         <small> {{$purchaseProduct->product?->title}},</small>
                    @endforeach
                </td>
                <td class="font-14">
                    <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                        <a href="{{route('purchase.edit', [$purchase->id])}}" class="mx-2 text-brand-primary"><i class="bi bi-pencil"></i></a>
                        <a href="{{route('purchase.show', [$purchase->id])}}" class="mx-2"><i class="bi bi-eye"></i></a>
                        <a href="javascript:void(0);" onclick="$(this).confirmDelete($('#delete-{{$key}}')) " class="mx-2 text-danger"><i class="bi bi-trash3"></i></a>
                        <form action="{{ route('purchase.destroy',$purchase->id) }}" method="post" id="delete-{{$key}}"> @csrf @method('delete') </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td class="text-center" colspan="15">
                    <h3 class="text-muted">Empty !</h3>
                </td>
            </tr>
        @endforelse
        <tr>
            <th colspan="5" class="text-center">Total</th>
            <th>{{$total_amount}}</th>
            <th>{{$paid_amount}}</th>
            <th>{{$due_amount}}</th>

        </tr>
        </tbody>
    </table>
</div>

<div class="py-3">
{{$purchases->appends(Request::all())->links()}}
</div>
