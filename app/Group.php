<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use SoftDeletes;

    public $fillable = [
        'title', 'type'
    ];

    public function receipts($year = null)
    {
        if ($year != null) {
            return $this->hasMany(Receipt::class)->whereRaw('YEAR(date) = ?', $year);
        } else {
            return $this->hasMany(Receipt::class);
        }
    }

    public function incomes($year = null)
    {
        if ($year != null) {
            return $this->hasMany(Income::class)->whereRaw('YEAR(date) = ?', $year);
        } else {
            return $this->hasMany(Income::class);
        }
    }
}
