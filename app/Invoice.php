<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;

    public $fillable = [
        'debtor_id', 'date', 'nr'
    ];

    protected $dates = ['date'];

    public function debtor($year = null)
    {
        return $this->belongsTo(Debtor::class);
    }

    public function rules()
    {
        return $this->belongsToMany(Invoice::class, 'invoice_rule')->withPivot('title','qty','price');
    }
}
