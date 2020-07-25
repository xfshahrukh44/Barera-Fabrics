<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class Client extends Model
{
    use HasApiTokens;
    
    protected $fillable = [
        'name', 'contact'
    ];

    public function master_transactions(){
    	return $this->hasMany('App\Models\MasterTransaction');
    }
}
