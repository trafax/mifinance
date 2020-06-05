<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Debtor extends Model
{
    use SoftDeletes;

    public $fillable = [
        'title'
    ];

    public function incomes()
    {
        return $this->hasMany(Income::class);
    }
}
