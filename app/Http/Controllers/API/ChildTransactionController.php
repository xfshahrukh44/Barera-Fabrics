<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChildTransaction;
use App\Models\MasterTransaction;


class ChildTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ChildTransaction::with('product','master_transaction.client', 'color')->paginate(5);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        for($i = 0; $i < $request->count; $i++)
        {
            ChildTransaction::create([
            'product_id'            =>  $request[$i]['product_id'],
            'master_transaction_id' =>  $request[$i]['master_transaction_id'],
            'quantity'              =>  $request[$i]['quantity'],
            'color_id'              =>  $request[$i]['color_id'],
            'price'                 =>  $request[$i]['price'],
            'discount'              =>  $request[$i]['discount'],
            'total'                 =>  $request[$i]['total'],
        ]);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return ChildTransaction::with('product','master_transaction.client', 'color')->where('master_transaction_id', $id)->get();
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function search()
    {
        if ($search = \Request::get('q')){
            if(preg_match('(2020|2021)', $search) > 0){
                $users = User::where(function($query) use ($search){
                    $query->where('created_at','LIKE',"%".Carbon::parse($search)->format('Y-m-d')."%");
                })->paginate(5);
                return $users;
            }
            else{
                $users = User::where(function($query) use ($search){
                    $query->where('id','LIKE',"%$search%")->orWhere('name','LIKE',"%$search%")->orWhere('email','LIKE',"%$search%")->orWhere('type','LIKE',"%$search%");
                })->paginate(5);
                return $users;
            }
        }
        else{
            return User::orderBy('id', 'desc')->paginate(5);
        }
    }

}
