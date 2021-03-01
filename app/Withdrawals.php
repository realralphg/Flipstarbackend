<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Withdrawals extends Model
{
    protected $fillable = [
        'user_id','amount','amount_before','amount_after','is_complete','description'
    ];
}
