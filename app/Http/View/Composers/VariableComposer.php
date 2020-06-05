<?php

namespace App\Http\View\Composers;

use App\Receipt;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class VariableComposer
{
    public function compose(View $view)
    {
        $bookyears = array_unique(Receipt::select(DB::raw('YEAR(date) as `year`'))->groupBy('year', 'id')->get()->pluck('year')->toArray());

        $view->with('bookyears', $bookyears);
    }
}
