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

    public function incomes($year = null)
    {
        if ($year != null) {
            return $this->hasMany(Income::class)->whereRaw('YEAR(date) = ?', $year);
        } else {
            return $this->hasMany(Income::class);
        }
    }
}
