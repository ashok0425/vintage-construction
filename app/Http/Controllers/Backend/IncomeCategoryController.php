<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\ExpenseCategoryRequest;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;

class IncomeCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::user()->can('manage_income_category')) {
            return redirect('home')->with(denied());
        } // end permission checking


        return view('backend.income-category.index',[
            'categories' => Auth::user()->business->expenseCategory()->where('type',0)->orderBY('id', 'DESC')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExpenseCategoryRequest $request)
    {
        if (!Auth::user()->can('manage_income_category')) {
            return redirect('home')->with(denied());
        } // end permission checking

        $category = new ExpenseCategory();
        $category->name = $request->category['name'];
        $category->type=0;
        $category->business_id = Auth::user()->business_id;
        $category->save();
        return response($category);
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ExpenseCategoryRequest $request, $id)
    {
        if (!Auth::user()->can('manage_expense_category')) {
            return redirect('home')->with(denied());
        } // end permission checking

        $category = Auth::user()->business->expenseCategory()->findOrFail($id);
        $category->name = $request->category['name'];
        $category->save();
        return response()->json(['success', 'Category has been updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Auth::user()->can('manage_income_category')) {
            return redirect('home')->with(denied());
        } // end permission checking

        if (Expense::where('expense_category_id',$id)->first()) {
            return response()->json(['error', "Category can't be deleted"]);
        }
        Auth::user()->business->expenseCategory()->where('id',$id)->delete();
        return response()->json(['success', 'Category has been deleted']);
    }
}
