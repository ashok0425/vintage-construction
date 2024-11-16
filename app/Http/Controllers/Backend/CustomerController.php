<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use App\Models\Sell;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Toastr;
use File;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::user()->can('manage_customer')) {
            return redirect('home')->with(denied());
        } // end permission checking

        return view('backend.customer.index',[
            'customers' => Auth::user()->business->customer()->orderBy('id', 'DESC')->with('sells')->paginate(20)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::user()->can('manage_customer')) {
            return redirect('home')->with(denied());
        } // end permission checking


        return view('backend.customer.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerRequest $request)
    {
        if (!Auth::user()->can('manage_customer')) {
            return redirect('home')->with(denied());
        } // end permission checking

        $customer = new Customer();
        $customer->fill($request->all());
        if($request->hasFile('photo')){
            $customer->photo = $request->photo->move('uploads/customer/', Str::random(20) . '.' . $request->photo->extension());;
        }
        $customer->business_id=Auth::user()->business_id;
        $customer->save();
        Toastr::success('Customer has been created', '', ['progressBar' => true, 'closeButton' => true, 'positionClass' => 'toast-bottom-right']);
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
