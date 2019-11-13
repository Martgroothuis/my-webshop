<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\ProductVersion;
use App\Size;

use App\Http\Requests;


class ShoppingCartController extends Controller
{
    // use Session;

    public function index(Request $request)
    {
        //    
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
     * @param  \App\Sizes  $sizes
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        return view('shoppingcart.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Sizes  $sizes
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Sizes  $sizes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Sizes  $sizes
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        // 
    }
}
