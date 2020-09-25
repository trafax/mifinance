<?php

namespace App\Http\Controllers;

use App\Group;
use App\Receipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use \Gumlet\ImageResize;

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

            $receipts = Receipt::with('group')->where(function($q) use ($request) {
                $q->whereRaw('lower(title) LIKE ?', "%".strtolower($request->get('search'))."%")->orWhereHas('group', function($q) use ($request) {
                    $q->whereRaw('lower(title) LIKE ?', "%".strtolower($request->get('search'))."%");
                });
            })->whereRaw('YEAR(date) = ?', session()->get('bookyear') ?? date('Y'))
            ->orderBy('date', 'DESC')->paginate(50)->appends(request()->query());

        } else {

            $receipts = Receipt::whereRaw('YEAR(date) = ?', session()->get('bookyear') ?? date('Y'))->orderBy('date', 'DESC')->paginate(50);
        }

        $receipt_nr = Str::lower(Str::random(5));

        return view('receipt_index')->with([
            'groups' => $groups,
            'receipts' => $receipts,
            'receipt_nr' => $receipt_nr
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

        if ($request->has('receipt_file')) {
            $path = $request->file('receipt_file')->store('receipts', 'public');
            $allowedMimeTypes = ['image/jpeg','image/gif','image/png','image/bmp','image/svg+xml'];
            $contentType = mime_content_type('storage/'.$path);

            if(in_array($contentType, $allowedMimeTypes) ){
                $image = new ImageResize('storage/'.$path);
                //$image->scale(50);
                $image->resizeToWidth(450);
                $image->save('storage/'.$path);
            }
            $request->request->set('file', $path);
        }

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

        $receipt_nr = Str::lower(Str::random(5));

        return view('receipt_edit')->with([
            'receipt' => $receipt,
            'groups' => $groups,
            'receipt_nr' => $receipt_nr
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
        if ($request->has('receipt_file')) {

            $path = $request->file('receipt_file')->store('receipts', 'public');

            $allowedMimeTypes = ['image/jpeg','image/gif','image/png','image/bmp','image/svg+xml'];
            $contentType = mime_content_type('storage/'.$path);

            if(in_array($contentType, $allowedMimeTypes) ){
                $image = new ImageResize('storage/'.$path);
                //$image->scale(50);
                $image->resizeToWidth(450);
                $image->save('storage/'.$path);
            }

            $request->request->set('file', $path);
        }
        $receipt->fill($request->all());
        $receipt->save();

        return redirect()->to($request->get('referrer'))->with('status', 'Bonnetje succesvol aangepast.');
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
