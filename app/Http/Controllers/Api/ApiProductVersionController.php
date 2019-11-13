<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\ProductVersion;
use App\Color;
use App\Size;

class ApiProductVersionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($product)
    {
        $productVersions = ProductVersion::Where('product_id', '=', $product)
            ->with(['Color'])
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

        return $productVersions;
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
        $productVersion = ProductVersion::create($request->all());

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
        $productVersions = ProductVersion::Where('product_id', '=', $product)
            ->with(['color'])
            ->with(['size.waistSize', 'size.lengthSize'])
            ->get();


        // $i = 0;

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

        return $productVersions;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\product_version  $product_version
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductVersion $productVersion)
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
    public function update(Request $request, ProductVersion $productversion)
    {
        $productversion->update($request->all());

        return response()->json($productversion, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\product_version  $product_version
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductVersion $productversion)
    {
        $productversion->delete();
        $productversion->image()->delete();

        return response()->json(null, 204);
    }
}
