<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Client::with('master_transactions.child_transactions.color', 'master_transactions.child_transactions.product')->paginate(5);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Client::create([
            'name'            =>$request['name'],
            'contact' =>$request['contact'],
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
        $client = Client::find($id);
        return $client->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Client::find($id)->delete();
    }

    public function search()
    {
        if ($search = \Request::get('q')){
            if(preg_match('(2020|2021)', $search) > 0){
                $client = Client::where(function($query) use ($search){
                    $query->where('created_at','LIKE',"%".Carbon::parse($search)->format('Y-m-d')."%");
                })->paginate(5);
                return $client;
            }
            else{
                $client = Client::where(function($query) use ($search){
                    $query->where('id','LIKE',"%$search%")->orWhere('name','LIKE',"%$search%")->orWhere('contact','LIKE',"%$search%");
                })->paginate(5);
                return $client;
            }
        }
        else{
            return Client::orderBy('id', 'desc')->paginate(5);
        }
    }

    public function get_clients()
    {
        return Client::all();
    }

    public function get_client_data(Request $request)
    {
        return Client::find($request->q)->contact;
    }
}
