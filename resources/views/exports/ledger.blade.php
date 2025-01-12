<div class="table-responsive">
    <table class="table table-bordered wiz-table text-center mw-col-width-skip-first">
        <thead>
        <tr class="bg-secondary text-white">
            <th scope="col" style="width: 60px">{{__('pages.sl')}}</th>
            <th scope="col">{{__('pages.date')}}</th>
            <th scope="col">Debit</th>
            <th scope="col">Credit</th>
            <th scope="col">Type</th>
            <th>Remark</th>
        </tr>
        </thead>
        <tbody>
        @php
            $debit = 0;
            $credit = 0;
        @endphp
        @foreach($ledgers as $key => $ledger)
            @php
                $debit += $ledger['debit'];
                $credit += $ledger['credit'];
            @endphp

            <tr class="">
                <td>{{$key+1}}</td>

                <td>@formatdate($ledger['date'])</td>
                <td>{{$ledger['debit']??'-'}}</td>
                <td>{{$ledger['credit']??'-'}}</td>
                <td>{{$ledger['type']??'-'}}</td>
                <td>{{$ledger['remark']??'-'}}</td>

            </tr>
        @endforeach

        <tr>
                <td colspan="2"><b>Total:</b></td>

            <td>{{get_option('app_currency')}} <b>{{number_format($debit, 2)}}</b></td>
            <td>{{get_option('app_currency')}} <b>{{number_format($credit, 2)}}</b></td>
            {{-- <td>{{get_option('app_currency')}}  <b>{{number_format($total_income - $total_expense, 2)}}</b></td> --}}
        </tr>
        </tbody>
    </table>

</div>
