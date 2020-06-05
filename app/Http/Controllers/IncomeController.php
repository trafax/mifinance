<?php

namespace App\Http\Controllers;

use App\Debtor;
use App\Group;
use App\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $groups = Group::where('type', 'income')->orderBy('title', 'ASC')->get();
        $debtors = Debtor::orderBy('title', 'ASC')->get();

        if ($request->get('search')) {
            $incomes = Income::with('group')->where(function($q) use ($request) {
                $q->whereRaw('lower(title) LIKE ?', "%".strtolower($request->get('search'))."%")->orWhereHas('group', function($q) use ($request) {
                    $q->whereRaw('lower(title) LIKE ?', "%".strtolower($request->get('search'))."%");
                })->orWhereHas('debtor', function($q) use ($request) {
                    $q->whereRaw('lower(title) LIKE ?', "%".strtolower($request->get('search'))."%");
                });
            })->whereRaw('YEAR(date) = ?', session()->get('bookyear') ?? date('Y'))
            ->orderBy('date', 'DESC')->paginate(50)->appends(request()->query());
        } else {
            $incomes = Income::whereRaw('YEAR(date) = ?', session()->get('bookyear') ?? date('Y'))->orderBy('date', 'DESC')->paginate(50);
        }

        return view('income_index')->with([
            'groups' => $groups,
            'debtors' => $debtors,
            'incomes' => $incomes
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $income = new Income();
        $income->fill($request->all());
        $income->save();

        return redirect()->back()->with('status', 'Inkomen succesvol toegevoegd.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Income $income)
    {
        $groups = Group::where('type', 'income')->orderBy('title', 'ASC')->get();
        $debtors = Debtor::orderBy('title', 'ASC')->get();

        return view('income_edit')->with([
            'income' => $income,
            'debtors' => $debtors,
            'groups' => $groups
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Income $income)
    {
        $income->fill($request->all());
        $income->save();

        return redirect()->route('income.index')->with('status', 'Inkomen succesvol aangepast.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Income $income)
    {
        $income->delete();

        Session::flash('status', 'Inkomen succesvol verwijderd.');
    }
}
