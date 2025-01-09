<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use App\Models\Sell;
use App\Http\Controllers\Controller;
use App\Models\Investment;
use Illuminate\Support\Str;
use Toastr;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvestmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::user()->can('manage_investment')) {
            return redirect('home')->with(denied());
        } // end permission checking

        return view('backend.investment.index',[
            'investments' => Auth::user()->business->investment()->orderBy('id', 'DESC')->with(['user','customer'])->paginate(20)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::user()->can('manage_investment')) {
            return redirect('home')->with(denied());
        } // end permission checking


        return view('backend.investment.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Auth::user()->can('manage_investment')) {
            return redirect('home')->with(denied());
        } // end permission checking

        $customer = new Investment();
        $customer->user_id=$request->user_id;
        $customer->customer_id=$request->customer_id;
        $customer->investment_date=$request->investment_date;
        $customer->amount=$request->amount;
        $customer->remark=$request->remark;
        $customer->business_id=Auth::user()->business_id;
        $customer->save();
        Toastr::success('Investment has been created', '', ['progressBar' => true, 'closeButton' => true, 'positionClass' => 'toast-bottom-right']);
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Auth::user()->can('manage_customer')) {
            return redirect('home')->with(denied());
        } // end permission checking

        $sells = Sell::where('customer_id', $id)->orderBy('id', 'DESC')->paginate(20);

        $customer = Customer::findOrFail($id);

        return view('backend.customer.show',[
            'customer' => $customer,
            'sells' => $sells,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Auth::user()->can('manage_customer')) {
            return redirect('home')->with(denied());
        } // end permission checking


        return view('backend.customer.edit',[
            'customer' => Customer::findOrFail($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerRequest $request, $id)
    {
        if (!Auth::user()->can('manage_customer')) {
            return redirect('home')->with(denied());
        } // end permission checking
        $customer = Customer::findOrFail($id);
        $customer->fill($request->all());
        if($request->hasFile('photo')){
            File::delete($customer->photo);
            $customer->photo = $request->photo->move('uploads/customer/', Str::random(20) . '.' . $request->photo->extension());;
        }
        $customer->save();
        Toastr::success('Customer has been updated', '', ['progressBar' => true, 'closeButton' => true, 'positionClass' => 'toast-bottom-right']);
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Auth::user()->can('manage_customer')) {
            return redirect('home')->with(denied());
        } // end permission checking


        $customer = Customer::findOrFail($id);
        if ($customer->sells->count() > 0){
            Toastr::error('You can not delete this customer. this customer already have '. $customer->sells->count() .' sell', '', ['progressBar' => true, 'closeButton' => true, 'positionClass' => 'toast-bottom-right']);
        }else{
            File::delete($customer->photo);
            Customer::destroy($id);
            Toastr::error('Customer has been deleted', '', ['progressBar' => true, 'closeButton' => true, 'positionClass' => 'toast-bottom-right']);
        }
        return redirect()->back();

    }
}
