<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\ExpenseRequest;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Toastr;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!Auth::user()->can('manage_expense')) {
            return redirect('home')->with(denied());
        } // end permission checking

        $expenses = Auth::user()->business->expense()->where('type',1)->orderBY('id', 'DESC')
        ->when(!Auth::user()->can('do anything'),function($query){
            return $query->where('customer_id', Auth::user()->customer_id);
        });

        if($request->other){
            $expenses=$expenses->whereNotNull('vehicle_id')->when($request->vehicle_id,function($query) use($request){
                return  $query->where('vehicle_id',$request->vehicle_id);
            });
            if ($request->start_date != '' || $request->end_date != ''){
                $start_date = $request->start_date ? $request->start_date : Expense::oldest()->pluck('expense_date')->first();
                $end_date = $request->end_date ? $request->end_date : Expense::latest()->pluck('expense_date')->first();
                $expenses = $expenses->whereBetween('expense_date', [$start_date, $end_date]);
            }
            if ($request->customer_id){
                $expenses = $expenses->where('customer_id', $request->customer_id);
            }
            $expenses=$expenses->paginate(50);
            return view('backend.expense.index',[
                'expenses'=>$expenses,
                'vehicles'=>Auth::user()->business->vehicle,
                'customers'=> Customer::where('business_id',Auth::user()->business_id)->when(!Auth::user()->can('do anything'),function($query){
            return $query->where('id', Auth::user()->customer_id);
        })->get()
            ]);

        }

        if ($request->expense_id){
            $expenses = $expenses->where('expense_id', 'like', '%'.$request->expense_id.'%');
        }


        if ($request->expense_category_id){
            $expenses = $expenses->where('expense_category_id', $request->expense_category_id);
        }
        if ($request->customer_id){
            $expenses = $expenses->where('customer_id', $request->customer_id);
        }

        if ($request->start_date != '' || $request->end_date != ''){
            $start_date = $request->start_date ? $request->start_date : Expense::oldest()->pluck('expense_date')->first();
            $end_date = $request->end_date ? $request->end_date : Expense::latest()->pluck('expense_date')->first();

            $expenses = $expenses->whereBetween('expense_date', [$start_date, $end_date]);
        }


        $expenses = $expenses->whereNull('vehicle_id')->paginate(50);
        return view('backend.expense.index',[
            'expenses' => $expenses,
            'expense_categories' => Auth::user()->business->expenseCategory()->get(),
           'customers'=> Customer::where('business_id',Auth::user()->business_id)->when(!Auth::user()->can('do anything'),function($query){
            return $query->where('id', Auth::user()->customer_id);
        })->get()

        ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::user()->can('manage_expense')) {
            return redirect('home')->with(denied());
        } // end permission checking

        return view('backend.expense.create',[
        'expense_categories' => ExpenseCategory::where('business_id',Auth::user()->business_id)->where('type',1)->get(),
           'customers'=> Customer::where('business_id',Auth::user()->business_id)->when(!Auth::user()->can('do anything'),function($query){
            return $query->where('id', Auth::user()->customer_id);
        })->get(),
        'vehicles'=>Auth::user()->business->vehicle,

        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExpenseRequest $request)
    {
        if (!Auth::user()->can('manage_expense')) {
            return redirect('home')->with(denied());
        } // end permission checking


        $expense = new Expense();
        $expense->fill($request->all());
        $expense->type=1;
        $expense->business_id = Auth::user()->business_id;
        $expense->customer_id = $request->customer_id??Auth::user()->customer_id;
        $expense->amount = $request->amount??0;
        $expense->save();

        Toastr::success('Expense successfully saved', '', ['progressBar' => true, 'closeButton' => true, 'positionClass' => 'toast-bottom-right']);
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
        if (!Auth::user()->can('manage_expense')) {
            return redirect('home')->with(denied());
        } // end permission checking

        $expenses =  Auth::user()->business->expense()->where('id',$id)->findOrFail($id);
        if (!Auth::user()->can('access_to_all_branch')) {
            if ($expenses->business_id != Auth::user()->business_id){
                return redirect()->back()->with(denied());
            }
        }

        return view('backend.expense.edit',[
            'expense' => $expenses,
            'expense_categories' => ExpenseCategory::where('business_id',Auth::user()->business_id)->where('type',1)->get(),
           'customers'=> Customer::where('business_id',Auth::user()->business_id)->when(!Auth::user()->can('do anything'),function($query){
            return $query->where('id', Auth::user()->customer_id);
        })->get()

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
        if (!Auth::user()->can('manage_expense')) {
            return redirect('home')->with(denied());
        } // end permission checking

        $expenses =  Auth::user()->business->expense()->where('id',$id)->findOrFail($id);
        if (!Auth::user()->can('access_to_all_branch')) {
            if ($expenses->business_id != Auth::user()->business_id){
                return redirect()->back()->with(denied());
            }
        }

        $expense = $expenses;
        $expense->fill($request->all());
        $expense->customer_id = $request->customer_id??Auth::user()->customer_id;
        $expense->save();

        Toastr::success('Expense successfully updated', '', ['progressBar' => true, 'closeButton' => true, 'positionClass' => 'toast-bottom-right']);
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
        if (!Auth::user()->can('manage_expense')) {
            return redirect('home')->with(denied());
        } // end permission checking

        Auth::user()->business->expense()->where('id',$id)->delete();
        Toastr::error('Expense successfully deleted', '', ['progressBar' => true, 'closeButton' => true, 'positionClass' => 'toast-bottom-right']);
        return redirect()->back();
    }
}
