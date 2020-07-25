<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class ChildTransaction extends Model
{
	use HasApiTokens;

    protected $fillable = [
        'product_id', 'master_transaction_id', 'color_id', 'quantity', 'price', 'discount', 'total'
    ];
    
    public function master_transaction(){
        return $this->belongsTo('App\Models\MasterTransaction');
    }

    public function product(){
    	return $this->belongsTo('App\Models\Product');
    }

    public function color(){
    	return $this->belongsTo('App\Models\Color');
    }

}
