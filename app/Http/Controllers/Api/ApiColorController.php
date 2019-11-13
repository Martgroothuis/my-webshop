<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Color;

class ApiColorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Color::all();
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
        $color = Color::create($request->all());
        return Color::find($color->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\color  $color
     * @return \Illuminate\Http\Response
     */
    public function show(color $color)
    {
        return Color::find($color);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\color  $color
     * @return \Illuminate\Http\Response
     */
    public function edit(color $color)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\color  $color
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, color $color)
    {
        $color->update($request->all());

        return response()->json($color, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\color  $color
     * @return \Illuminate\Http\Response
     */
    public function destroy(color $color)
    {
        $color->delete();

        return response()->json(null, 204);
    }
}
