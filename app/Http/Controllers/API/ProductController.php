<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Carbon\Carbon;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Product::with('child_transactions.master_transaction.client', 'child_transactions.color')->paginate(5);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Product::create([
            'name'            =>$request['name'],
            'price'            =>$request['price'],
        ]);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        return $product->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Product::find($id)->delete();
    }

    public function get_product()
    {
        return Product::all();
    }

    public function search(Request $request)
    {
        if($request->q)
        {
            if(preg_match('(2020|2021)', $request->q) > 0)
            {
                return Product::where('created_at','LIKE','%'.Carbon::parse($request->q)->format('Y-m-d').'%')
                                    ->paginate(5);
            }
            else
            {
                return Product::where('id','LIKE',"%".$request->q."%")
                                    ->orWhere('name','LIKE',"%".$request->q."%")
                                    ->orWhere('price','LIKE',"%".$request->q."%")
                                    ->paginate(5);
            }
        }
        else
        {
            return Product::orderBy('id', 'desc')->paginate(5);
        }
    }

    public function get_product_data(Request $request)
    {
        return Product::find($request->q)->price;
    }
}
