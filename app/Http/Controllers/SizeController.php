<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Size;
use App\Product;

class SizeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
    public function show(Product $product)
    {
        $productVersions = $product->productVersion;
        foreach ($productVersions as $productVersion) {
            $productVersion->size;
        }
        foreach ($productVersions as $productVersion) {
            foreach ($productVersion->size as $sizes) {
                $sizes->waistSize;
                $sizes->lengthSize;
            }
        }
        return $productVersions;
        return Size::all();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Sizes  $sizes
     * @return \Illuminate\Http\Response
     */
    public function edit(Sizes $sizes)
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
    public function update(Request $request, Sizes $sizes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Sizes  $sizes
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sizes $sizes)
    {
        //
    }
}
