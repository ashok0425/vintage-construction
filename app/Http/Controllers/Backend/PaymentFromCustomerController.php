<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\PaymentFromCustomerRequest;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\PaymentFromCustomer;
use App\Models\Sell;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Toastr;

class PaymentFromCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!Auth::user()->can('manage_customer_payment')) {
            return redirect('home')->with(denied());
        } // end permission checking

       $sells = Auth::user()->business->sell()
           ->where('due_amount', '>', 'paid_amount');

       if ($request->customer_id){
           $sells = $sells->where('customer_id', $request->customer_id);
       }

        if ($request->start_date != '' || $request->end_date != ''){
            $start_date = $request->start_date ? $request->start_date : Sell::oldest()->pluck('sell_date')->first();
            $end_date = $request->end_date ? $request->end_date : Sell::latest()->pluck('sell_date')->first();

            $sells = $sells->whereBetween('sell_date', [$start_date, $end_date]);
        }

        $sells = $sells->with('customer')->paginate(50);


        return view('backend.payment.customer.index',[
            'due_sells' => $sells,
            'customers' => Auth::user()->business->customer,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::user()->can('manage_customer_payment')) {
            return redirect('home')->with(denied());
        } // end permission checking

        return view('backend.payment.customer.create',[
            'customers' => Auth::user()->business->customer()->where('status', 1)->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PaymentFromCustomerRequest $request)
    {
        if (!Auth::user()->can('manage_supplier_payment')) {
            return redirect('home')->with(denied());
        } // end permission checking

        $sell = Auth::user()->business->sell()->findOrFail($request->sell_id);
        if ($sell->due_amount >= $request->amount ){
            $sell->paid_amount = $sell->paid_amount + $request->amount;
            $sell->due_amount = $sell->due_amount - $request->amount;
            $sell->save();

            $payment = new PaymentFromCustomer();
            $payment->fill($request->all());
            $payment->customer_id = $sell->customer_id;
            $payment->sell_id = $sell->id;
            $payment->save();

            Toastr::success('Payment successfully saved', '', ['progressBar' => true, 'closeButton' => true, 'positionClass' => 'toast-bottom-right']);
            return redirect()->back();
        }else{
            Toastr::error('Paid amount should be smaller than or equal of due amount', '', ['progressBar' => true, 'closeButton' => true, 'positionClass' => 'toast-bottom-right']);
            return redirect()->back();
        }
    }

    public function pdf(Request $request)
    {
        $business_id = $request->business_id ? [$request->business_id] : PaymentFromCustomer::select('business_id')->distinct()->get();
        $customer_id = $request->customer_id ? [$request->customer_id] : PaymentFromCustomer::select('customer_id')->distinct()->get();
        $start_date = $request->start_date ? $request->start_date : PaymentFromCustomer::oldest()->pluck('payment_date')->first();
        $end_date = $request->end_date ? $request->end_date : PaymentFromCustomer::latest()->pluck('payment_date')->first();

        $payments = PaymentFromCustomer::whereIn('business_id', $business_id)
            ->whereIn('customer_id', $customer_id)
            ->whereBetween('payment_date', [$start_date, $end_date])
            ->orderBY('id', 'DESC')
            ->get();



        if ($request->business_id != ''){
            $branch =  Branch::findOrFail($request->business_id);
            $branch_name = $branch->title;
        }else{
            $branch_name = 'All';
        }

        if ($request->customer_id != ''){
            $customer =  Customer::findOrFail($request->customer_id);
            $customer_name = $customer->name;
        }else{
            $customer_name = 'All';
        }


        $filter_by = [];
        $filter_by['branch_name'] = $branch_name;
        $filter_by['customer_name'] = $customer_name;
        $filter_by['start_date'] = Carbon::parse($start_date)->format(get_option('app_date_format'));
        $filter_by['end_date'] = Carbon::parse($end_date)->format(get_option('app_date_format'));;

        $random_string = Str::random(10);
        $pdf = PDF::loadView('backend.pdf.payment.customer', compact('payments', 'filter_by'))->setPaper('a4');

        $pdf->save(public_path() . '/pdf/payment/customer/' . 'payment-from-customer-' . Carbon::now()->format('Y-m-d') . '-'. $random_string . '.pdf');
        return redirect('/pdf/payment/customer/' . 'payment-from-customer-' . Carbon::now()->format('Y-m-d') . '-'. $random_string .'.pdf');
    }
}
