<?php

namespace App\Http\Controllers\Backend;

use App\Models\Branch;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class StockReportController extends Controller
{
    public function index()
    {
        if (!Auth::user()->can('view_stock')) {
            return redirect('home')->with(denied());
        } // end permission checking


        $products = Auth::user()->business->product;
        return view('backend.report.stock.index',[
            'products' => $products
        ]);
    }

    public function filter(Request $request){
        if (!Auth::user()->can('view_stock')) {
            return redirect('home')->with(denied());
        } // end permission checking

        if ($request->business_id != ''){
            $products = Product::with('productStockHistories', 'unit')->get();

            $product_requisitions = [];
            foreach ($products as $key => $product){
                $productStockHistories = $product->productStockHistories->where('business_id', $request->business_id);

                $product_requisitions[$key]['product_id'] = $product->id;
                $product_requisitions[$key]['product_title'] = $product->title;
                $product_requisitions[$key]['product_sku'] = $product->sku;
                $product_requisitions[$key]['product_sell_price'] = $product->sell_price;
                $product_requisitions[$key]['purchase_qty'] = $productStockHistories->sum('purchase_qty');
                $product_requisitions[$key]['branch_requisitions_from_qty'] = $productStockHistories->sum('req_send'); // Stock Out
                $product_requisitions[$key]['sell_qty'] = $productStockHistories->sum('sell_qty');

                $product_requisitions[$key]['branch_requisitions_to_qty'] = $productStockHistories->sum('req_received');;
                $product_requisitions[$key]['current_stock'] = ($product_requisitions[$key]['purchase_qty'] + $product_requisitions[$key]['branch_requisitions_from_qty']) - ($product_requisitions[$key]['sell_qty'] + $product_requisitions[$key]['branch_requisitions_to_qty']);
                $product_requisitions[$key]['unit'] = $product->unit->title ?? '';

            }


            return view('backend.report.stock.filter',[
                'product_requisitions' => $product_requisitions,
            ]);
        }else{
            return redirect(url('report/stock-report'));
        }
    }

    public function stockReportPdf(Request $request){
        if (!Auth::user()->can('view_stock')) {
            return redirect('home')->with(denied());
        } // end permission checking

        $products = Product::all();

        $random_string = Str::random(10);
        $pdf = PDF::loadView('backend.pdf.reports.stock.all-branch', compact('products', 'request'))->setPaper('a4');

        if ($request->action_type == 'download'){
            return $pdf->download('stock-report-' . Carbon::now()->format(get_option('app_date_format')) . '-'. $random_string . '.pdf');
        }elseif($request->action_type == 'print'){
            @unlink('pdf/reports/stock/' . 'stock-report.pdf');
            $pdf->save('pdf/reports/stock/' . 'stock-report.pdf');
            return redirect('pdf/reports/stock/' . 'stock-report.pdf');
        }else{

            $headers = [
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0'
                , 'Content-type' => 'text/csv'
                , 'Content-Disposition' => 'attachment; filename=galleries.csv'
                , 'Expires' => '0'
                , 'Pragma' => 'public'
            ];

            $filename = 'download.csv';
            $handle = fopen($filename, 'w');
            fputcsv($handle, [
                __('pages.sl'),
                __('pages.product'),
                __('pages.purchase') . ' '. __('pages.quantity'),
                __('pages.sells') . ' '. __('pages.quantity'),
                __('pages.current_stock_quantity'),
                __('pages.current_stock_value'),
            ]);

            $products = Product::all();
            foreach ($products as $key => $product) {

                   $purchaseQuantity = $product->purchaseProducts->sum('quantity');
                    $sellQuantity = $product->sellProducts->sum('quantity');
                    $current_stock_qty = $product->purchaseProducts->sum('quantity') - $product->sellProducts->sum('quantity');


                $product_tax = $product->sell_price * $product->tax->value / 100;
                $current_stock_amount = $current_stock_qty * $product->sell_price;


                fputcsv($handle, [
                    $key + 1,
                    $product->title . '|'. $product->sku,
                    $purchaseQuantity,
                    $sellQuantity,
                    $current_stock_qty,
                    number_format($current_stock_amount,2),
                ]);
            }

            fclose($handle);
            return response()->download($filename, 'stock-report-' . Carbon::now()->toDateString() . '.csv', $headers);

        }
    }


    public function ledger(Request $request){
        $purchases=Auth::user()->business->purchase()->when(!Auth::user()->can('do anything'),function($query){
            return $query->where('customer_id', Auth::user()->customer_id);
        })->when($request->customer_id,function($query) use ($request){
            $query->where('customer_id', $request->customer_id);
       })->get();

        $expenses=Auth::user()->business->expense()->when(!Auth::user()->can('do anything'),function($query){
            return $query->where('customer_id', Auth::user()->customer_id);
        })->when($request->customer_id,function($query) use ($request){
            $query->where('customer_id', $request->customer_id);
       })->where('type',1)->get();

       $income=Auth::user()->business->expense()->when(!Auth::user()->can('do anything'),function($query){
        return $query->where('customer_id', Auth::user()->customer_id);
    })->when($request->customer_id,function($query) use ($request){
        $query->where('customer_id', $request->customer_id);
   })->where('type',0)->get();

        $purchaseArray = $purchases->map(function($purchase) {
            $productId=$purchase->purchaseProducts->pluck('product_id')->toArray();
            $products=Product::whereIn('id',$productId)->pluck('title')->toArray();
            $imp=implode(',',$products);
            return [
                'id' => $purchase->id,
                'date' => $purchase->purchase_date,
                'credit' => $purchase->total_amount,
                 'debit'=>null,
                'type' => 'Purchase',
                'remark'=>"purchase: ".$imp
            ];
        })->toArray();




        $expensesArray = $expenses->map(function($expense) {
            return [
                'id' => $expense->id,
                'date' => $expense->expense_date,
                'debit' => $expense->amount,
                'credit'=>null,
                'type' => 'Expense',
                'remark'=>$expense->expenseCategory?->name.':'.$expense->note
            ];
        })->toArray();

        $incomeArray = $income->map(function($expense) {
            return [
                'id' => $expense->id,
                'date' => $expense->expense_date,
                'debit' => $expense->amount,
                'credit'=>null,
                'type' => 'Income',
                'remark'=>$expense->expenseCategory?->name.':'.$expense->note
            ];
        })->toArray();
        $ledgers=collect(array_merge($purchaseArray,$expensesArray,$incomeArray))
        ->sortByDesc('date')
        ->values();
        if ($request->type) {
            $ledgers = $ledgers->where('type', $request->type)->values();
        }
        if ($request->month) {
            $ledgers = $ledgers->filter(function ($entry) use ($request) {
                return Carbon::parse($entry['date'])->month ==  Carbon::parse($request->month)->month;
            })->values();
        }

        // Filter by year if provided
        if ($request->year) {
            $ledgers = $ledgers->filter(function ($entry) use ($request) {
                return Carbon::parse($entry['date'])->year ==  $request->year;
            })->values();
        }
        $ledgers=$ledgers->toArray();
        $customers= Customer::where('business_id',Auth::user()->business_id)->when(!Auth::user()->can('do anything'),function($query){
            return $query->where('id', Auth::user()->customer_id);
        })->get();
       return view('backend.report.ledger',compact('ledgers',
       'customers'
    ));

    }

}
