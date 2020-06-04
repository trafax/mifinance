<?php

namespace App\Http\Controllers;

use App\Debtor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DebtorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $debtors = Debtor::orderBy('title', 'ASC')->get();
        return view('debtor_index')->with([
            'debtors' => $debtors
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
        $debtor = new Debtor();
        $debtor->fill($request->all());
        $debtor->save();

        return redirect()->back()->with('status', 'Debiteur succesvol toegevoegd.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Debtor $debtor)
    {
        $debtor->fill($request->all());
        $debtor->save();

        echo 'done';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Debtor $debtor)
    {
        $debtor->delete();

        Session::flash('status', 'Debiteur succesvol verwijderd.');
    }
}
