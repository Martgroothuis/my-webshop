<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\LengthSize;

class ApiLengthSizeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return LengthSize::all();
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
        $lengthsize = LengthSize::create($request->all());

        return response()->json($lengthsize, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\LengthSize  $lengthSize
     * @return \Illuminate\Http\Response
     */
    public function show(LengthSize $lengthsize)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\LengthSize  $lengthSize
     * @return \Illuminate\Http\Response
     */
    public function edit(LengthSize $lengthsize)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\LengthSize  $lengthSize
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LengthSize $lengthsize)
    {
        $lengthsize->update($request->all());

        return response()->json($lengthsize, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\LengthSize  $lengthSize
     * @return \Illuminate\Http\Response
     */
    public function destroy(LengthSize $lengthsize)
    {
        $lengthsize->delete();

        return response()->json(null, 204);
    }
}
