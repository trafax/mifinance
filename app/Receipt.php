<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Receipt extends Model
{
    use SoftDeletes;

    public $fillable = [
        'group_id', 'date', 'title', 'description', 'price', 'paid_out'
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
