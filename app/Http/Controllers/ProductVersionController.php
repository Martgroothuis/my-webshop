<?php

namespace App\Http\Controllers;

use App\product_version;
use App\color;
use App\size;


use Illuminate\Http\Request;

class ProductVersionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($product)
    {
        $product_versions = Product_version::Where('product_id', '=', $product)
            ->with(['color'])
            ->get();
        $i = 0;
        // foreach ($product_versions as $product_version) {
        //     $i++;
        //     $product_version['order'] = $i;
        //     $product_version['color_version'] = $product_version->color->waterless === 1 ? 'Waterless®' : '';
        //     $product_version['color'] = $product_version->Color;
        //     $product_version['price'] = str_replace('.', ',', $product_version->price);
        //     $product_version->size;
        // }
        // foreach ($product_versions as $product_version) {
        //     foreach ($product_version->size as $sizes) {
        //         $sizes->Waist_size;
        //         $sizes->Length_size;
        //     }
        // }

        return $product_versions;
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
        $productVersion = product_version::create($request->all());

        return response()->json($productVersion, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\product_version  $product_version
     * @return \Illuminate\Http\Response
     */
    public function show($product)
    {
        $product_versions = Product_version::Where('product_id', '=', $product)
            ->with(['color'])
            ->with(['size.Waist_size', 'size.Length_size'])
            ->get();
        $i = 0;

        // foreach ($product_versions as $product_version) {
        //     $i++;
        //     $product_version['order'] = $i;
        //     $product_version['color_version'] = $product_version->color->waterless === 1 ? 'Waterless®' : '';
        //     $product_version['color'] = $product_version->Color;
        //     $product_version['price'] = str_replace('.', ',', $product_version->price);
        //     $product_version->size;
        // }
        // foreach ($product_versions as $product_version) {
        //     foreach ($product_version->size as $sizes) {
        //         $sizes->Waist_size;
        //         $sizes->Length_size;
        //     }
        // }

        return $product_versions;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\product_version  $product_version
     * @return \Illuminate\Http\Response
     */
    public function edit(product_version $product_version)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\product_version  $product_version
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, product_version $product_version)
    {
        $product_version->update($request->all());

        return response()->json($product_version, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\product_version  $product_version
     * @return \Illuminate\Http\Response
     */
    public function destroy(product_version $productversion)
    {
        $productversion->delete();

        return response()->json(null, 204);
    }
}
