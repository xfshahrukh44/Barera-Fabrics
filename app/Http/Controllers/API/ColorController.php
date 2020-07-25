<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Color;
use Carbon\Carbon;

class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Color::with('child_transactions.product', 'child_transactions.master_transaction.client')->paginate(5);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Color::create([
            'name'            =>$request['name'],
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
        $color = Color::find($id);
        return $color->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Color::find($id)->delete();
    }

    public function search()
    {
        if ($search = \Request::get('q')){
            if(preg_match('(2020|2021)', $search) > 0){
                $color = Color::where(function($query) use ($search){
                    $query->where('created_at','LIKE',"%".Carbon::parse($search)->format('Y-m-d')."%");
                })->paginate(5);
                return $color;
            }
            else{
                $color = Color::where(function($query) use ($search){
                    $query->where('id','LIKE',"%$search%")->orWhere('name','LIKE',"%$search%");
                })->paginate(5);
                return $color;
            }
        }
        else{
            return Color::orderBy('id', 'desc')->paginate(5);
        }
    }

    public function get_colors()
    {
        return Color::all();
    }
}
