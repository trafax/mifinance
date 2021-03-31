<?php

namespace App\Http\Controllers;

use App\Debtor;
use App\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::orderBy('date', 'DESC')->paginate(50);

        return view('invoice_index', [
            'invoices' => $invoices,
            'invoice_nr' => Invoice::select('nr')->max('nr') + 1
        ]);
    }

    public function store(Request $request)
    {
        $invoice = new Invoice();
        $invoice->fill($request->all());
        $invoice->save();

        foreach ($request->get('rules')['title'] as $key => $value) {

            if (empty($value)) {
                continue;
            }

            $title = $request->get('rules')['title'][$key] ?? NULL;
            $qty = $request->get('rules')['qty'][$key] ?? NULL;
            $price = ($request->get('rules')['price'][$key] ?? 0);


            $invoice->rules()->attach($invoice->id, ['title' => $title, 'qty' => $qty, 'price' => $price]);
        }

        return redirect()->back()->with('status', 'Factuur succesvol toegevoegd.');
    }

    public function edit(Invoice $invoice)
    {
        $debtors = Debtor::orderBy('title', 'ASC')->get();

        return view('invoice_edit')->with([
            'invoice' => $invoice,
            'debtors' => $debtors,
        ]);
    }

    public function update(Invoice $invoice, Request $request)
    {
        $invoice->fill($request->all());
        $invoice->save();

        $invoice->rules()->detach();
        foreach ($request->get('rules')['title'] as $key => $value) {

            if (empty($value)) {
                continue;
            }

            $title = $request->get('rules')['title'][$key] ?? NULL;
            $qty = $request->get('rules')['qty'][$key] ?? NULL;
            $price = ($request->get('rules')['price'][$key] ?? 0);


            $invoice->rules()->attach($invoice->id, ['title' => $title, 'qty' => $qty, 'price' => $price]);
        }

        return redirect()->route('invoice.index')->with('status', 'Factuur succesvol aangepast.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice $invoice)
    {
        //$invoice->rules()->detach();
        $invoice->delete();

        Session::flash('status', 'Factuur succesvol verwijderd.');
    }

    public function download(Invoice $invoice)
    {
        //$pdf = App::make('dompdf.wrapper');
        //$pdf->loadHTML('<h1>Test</h1>');
        //return $pdf->stream();

        $pdf = \PDF::loadView('invoice_pdf', ['invoice' => $invoice]);
        return $pdf->stream();
        //return $pdf->download('invoice.pdf');
    }
}
