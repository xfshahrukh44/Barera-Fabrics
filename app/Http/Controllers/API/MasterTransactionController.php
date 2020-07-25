<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterTransaction;
use App\Models\Client;
use Carbon\Carbon;


class MasterTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return MasterTransaction::with('child_transactions.color', 'child_transactions.product', 'client')->paginate(5);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $child_transaction_id = MasterTransaction::create($request->all());
        return $child_transaction_id->id;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return MasterTransaction::with('child_transactions.color', 'child_transactions.product', 'client')->find($id);
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
        $master_transaction = MasterTransaction::find($id);
        $master_transaction->update($request->all());
        return['message'=>'Successfull'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        MasterTransaction::find($id)->delete();
        return ['message'=>'User Deleted'];
    }

    public function search(Request $request)
    {
        if($request->q)
        {
            $client = Client::where('name','LIKE','%'.$request->q.'%')->get();
            
            if(count($client) > 0)
            {
                return MasterTransaction::with('child_transactions.color', 'child_transactions.product', 'client')->where('client_id',$client[0]->id)->paginate(5); 
            }

            else
            {
                if(preg_match('(2020|2021)', $request->q) > 0)
                {
                    return MasterTransaction::with('child_transactions.color', 'child_transactions.product', 'client')
                                        ->where('created_at','LIKE','%'.Carbon::parse($request->q)->format('Y-m-d').'%')
                                        ->paginate(5);

                }
                else
                {
                    return MasterTransaction::with('child_transactions.color', 'child_transactions.product', 'client')
                                        ->where('price','LIKE','%'.$request->q.'%')
                                        ->orWhere('discount','LIKE','%'.$request->q.'%')
                                        ->orWhere('total','LIKE','%'.$request->q.'%')
                                        ->paginate(5);

                }
                
            }

        }
        else
        {
            return MasterTransaction::with('child_transactions.color', 'child_transactions.product', 'client')->paginate(5);
        }
    }
}
