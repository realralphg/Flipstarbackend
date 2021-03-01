<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GameUsers extends Model
{
    protected $fillable = [
        'user_id','game_id','star'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
