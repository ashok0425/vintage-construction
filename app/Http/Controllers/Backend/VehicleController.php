<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\ExpenseRequest;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;
use Toastr;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!Auth::user()->can('manage_vehicle')) {
            return redirect('home')->with(denied());
        } // end permission checking

        $vehicles = Auth::user()->business->vehicle()->orderBY('id', 'DESC');


        $vehicles = $vehicles->paginate(50);
        return view('backend.vehicle.index',[
            'vehicles' => $vehicles
                ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::user()->can('manage_vehicle')) {
            return redirect('home')->with(denied());
        } // end permission checking

        return view('backend.vehicle.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Auth::user()->can('manage_vehicle')) {
            return redirect('home')->with(denied());
        } // end permission checking


        $expense = new Vehicle();
        $expense->fill($request->all());
        $expense->business_id = Auth::user()->business_id;
        $expense->save();

        Toastr::success('Vehicle successfully saved', '', ['progressBar' => true, 'closeButton' => true, 'positionClass' => 'toast-bottom-right']);
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Auth::user()->can('manage_vehicle')) {
            return redirect('home')->with(denied());
        } // end permission checking

        $vehicle =  Auth::user()->business->vehicle()->where('id',$id)->findOrFail($id);


        return view('backend.vehicle.edit',[
            'vehicle' => $vehicle
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!Auth::user()->can('manage_vehicle')) {
            return redirect('home')->with(denied());
        } // end permission checking

        $vehicle =  Auth::user()->business->vehicle()->where('id',$id)->findOrFail($id);
        $vehicle->fill($request->all());
        $vehicle->save();

        Toastr::success('vehicle successfully updated', '', ['progressBar' => true, 'closeButton' => true, 'positionClass' => 'toast-bottom-right']);
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
        if (!Auth::user()->can('manage_vehicle')) {
            return redirect('home')->with(denied());
        } // end permission checking

        Auth::user()->business->vehicle()->where('id',$id)->delete();
        Toastr::error('vehicle successfully deleted', '', ['progressBar' => true, 'closeButton' => true, 'positionClass' => 'toast-bottom-right']);
        return redirect()->back();
    }
}
