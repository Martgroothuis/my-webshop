<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\WaistSize;

class ApiWaistSizeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return WaistSize::all();
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
        $waistsize = WaistSize::create($request->all());

        return response()->json($waistsize, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\WaistSize  $waistSize
     * @return \Illuminate\Http\Response
     */
    public function show(WaistSize $waistsize)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\WaistSize  $waistSize
     * @return \Illuminate\Http\Response
     */
    public function edit(WaistSize $waistsize)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\WaistSize  $waistSize
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WaistSize $waistsize)
    {
        $waistsize->update($request->all());

        return response()->json($waistsize, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\WaistSize  $waistSize
     * @return \Illuminate\Http\Response
     */
    public function destroy(WaistSize $waistsize)
    {
        $waistsize->delete();

        return response()->json(null, 204);
    }
}
