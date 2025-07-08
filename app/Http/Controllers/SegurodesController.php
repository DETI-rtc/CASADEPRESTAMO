<?php

namespace App\Http\Controllers;

use App\Models\Segurodes;
use Illuminate\Http\Request;
use App\Models\Segurodesg;

class SegurodesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $seguro = Segurodesg::where('estado',1)->get();
        return $seguro;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Segurodes  $segurodes
     * @return \Illuminate\Http\Response
     */
    public function show(Segurodesg $segurodes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Segurodes  $segurodes
     * @return \Illuminate\Http\Response
     */
    public function edit(Segurodesg $segurodes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Segurodes  $segurodes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Segurodesg $segurodes,$id)
    {

        $segu = Segurodesg::findOrFail($request->id);
        //$idrol = Role::find($request->roles[0]['name']);
      //DD($idrol->slug);
       $segu->seg_des = $request->seg_des;
       $segu->save();
        //$segu->update($request->all());
         //DD($segu);
        
        return $segu;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Segurodes  $segurodes
     * @return \Illuminate\Http\Response
     */
    public function destroy(Segurodesg $segurodes)
    {
        //
    }
}
