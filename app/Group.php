<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use SoftDeletes;

    public $fillable = [
        'title'
    ];

    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }
}
