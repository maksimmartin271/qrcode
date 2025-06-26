<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Link extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'url_to',
        'url_from',
        'key',
        'edit_key_hash',
    ];

    protected $dates = ['deleted_at'];  
    //protected $hidden = ['key'];
}
