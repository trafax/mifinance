<?php

namespace App\Http\Controllers;

use App\Group;
use App\Receipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ReceiptController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $groups = Group::where('type', 'receipt')->orderBy('title', 'ASC')->get();

        if ($request->get('search')) {
            $receipts = Receipt::with('group')->whereRaw('lower(title) LIKE ?', "%".strtolower($request->get('search'))."%")->orWhereHas('group', function($q) use ($request) {
                $q->whereRaw('lower(title) LIKE ?', "%".strtolower($request->get('search'))."%");
            })->orderBy('date', 'DESC')->paginate(50)->appends(request()->query());
        } else {
            $receipts = Receipt::orderBy('date', 'DESC')->paginate(50);
        }

        return view('receipt_index')->with([
            'groups' => $groups,
            'receipts' => $receipts
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
        $receipt = new Receipt();
        $receipt->fill($request->all());
        $receipt->save();

        return redirect()->back()->with('status', 'Bonnetje succesvol toegevoegd.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Receipt $receipt)
    {
        $groups = Group::where('type', 'receipt')->orderBy('title', 'ASC')->get();

        return view('receipt_edit')->with([
            'receipt' => $receipt,
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
    public function update(Request $request, Receipt $receipt)
    {
        $receipt->fill($request->all());
        $receipt->save();

        return redirect()->route('receipt.index')->with('status', 'Bonnetje succesvol aangepast.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Receipt $receipt)
    {
        $receipt->delete();

        Session::flash('status', 'Bonnetje succesvol verwijderd.');
    }
}
