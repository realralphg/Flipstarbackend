<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = [
        'user_id','name','amount','numbers','is_completed'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function players()
    {
        return $this->hasMany('App\GameUsers');
    }
}
