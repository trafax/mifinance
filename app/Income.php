<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Income extends Model
{
    use SoftDeletes;

    public $fillable = [
        'group_id', 'debtor_id', 'date', 'title', 'description', 'price'
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function debtor($year = null)
    {
        return $this->belongsTo(Debtor::class);
    }
}
