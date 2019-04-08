<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = 'contacts';
    protected $guarded = ['id'];

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function phones()
    {
        return $this->hasMany('App\Phone');
    }
}
