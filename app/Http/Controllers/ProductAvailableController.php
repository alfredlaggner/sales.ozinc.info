<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\ProductProduct;
use DataTables;
use App\StockInventory;

class ProductAvailableController extends Controller
{
    public function index()
    {

        /*        return Datatables::of($all_products)
                    ->make(true);*/

        return view('product_stock.sellable');
    }

    public function ajax_sellable()
    {
        $products = ProductProduct::where('quantity', '>', 0)
            ->where('is_sellable', true)
            ->orderBy('quantity', 'desc')
            ->get();
        $all_products = [];
        foreach ($products as $t) {
            array_push($all_products, [
                'code' => $t->code,
                'name' => $t->name,
                'brand' => $t->brand,
                'quantity' => $t->quantity,
            ]);
        }
        return Datatables::of(collect($all_products))
            ->make(true);

        // return view('sales.product_sale.quantity', compact('all_products'))->render();

    }


    public function zero_sellable()
    {

/*        $zero_within = env('ZERO_SELLABLE');
        $now = Carbon::now();
        $zero_time_span = Carbon::now()->subDays(30)->format("Y-m-d");

        $zero_sellable = StockInventory::has('product')
            ->where('total_qty', 0)
            ->where('inventory_date', '>=', $zero_time_span)
            ->orderby('inventory_date', 'desc')
            ->get();

        $all_products = [];
        foreach ($zero_sellable as $t) {
            array_push($all_products, [
                'code' => $t->product->code,
                'name' => $t->product_name,
                'inventory_date' => $t->inventory_date
            ]);
        }
dd("xxx");*/
        return view('product_stock.zero_sellable');
    }

    public function ajax_zero_sellable()
    {
        $zero_within = env('ZERO_SELLABLE');
        $now = Carbon::now();
        $zero_time_span = Carbon::now()->subDays(30)->format("Y-m-d");

        $zero_sellable = StockInventory::has('product')
            ->where('total_qty', 0)
            ->where('inventory_date', '>=', $zero_time_span)
            ->orderby('inventory_date', 'desc')
            ->get();

        $all_products = [];
        foreach ($zero_sellable as $t) {
            array_push($all_products, [
                'brand' => $t->product->brand,
                'name' => $t->product_name,
                'inventory_date' => $t->inventory_date
            ]);
        }
        return Datatables::of(collect($all_products))
            ->make(true);
    }


}
