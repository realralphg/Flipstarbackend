<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserBank extends Model
{
    protected $fillable = [
        'user_id','account_number','account_name','bank_name','bank_branch','bank_id','recipient_code'
    ];
}
