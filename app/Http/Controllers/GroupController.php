<?php

namespace App\Http\Controllers;

use App\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $groups = Group::where('type', array_key_first($request->all()))->orderBy('title', 'ASC')->get();
        $total = number_format(Group::where('type', array_key_first($request->all()))->with('receipts')->get()->pluck('receipts')->collapse()->sum('price'), 2);

        return view('group_index')->with([
            'groups' => $groups,
            'total' => $total
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
        $group = new Group();
        $group->fill($request->all());
        $group->save();

        return redirect()->back()->with('status', 'Groep succesvol toegevoegd.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {
        $group->fill($request->all());
        $group->save();

        echo 'done';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        $group->delete();

        Session::flash('status', 'Groep succesvol verwijderd.');
    }
}
