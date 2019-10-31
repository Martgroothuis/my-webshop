<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\product_version;
use App\Size;

use App\Http\Requests;


class ShoppingCardController extends Controller
{
    // use Session;

    public function index(Request $request)
    {
        $shoppingcardproducts = $request->session()->get('selectedProduct');

        foreach ($shoppingcardproducts as $shoppingcardproduct) {
            $product_version = Product_version::Where('id', '=', $shoppingcardproduct['product_version_id'])->get()->first();
            $product_version['price'] = str_replace('.', ',', $product_version->price);
            $product_version['product_id'] = $product_version->Product;
            $product_version['size'] = Size::find($shoppingcardproduct['size_id'])->first();
            $product_version['color_id'] = $product_version->color;
            $product_version['color_version'] = $product_version->color->waterless === 1 ? 'Waterless®' : '';

            $shoppingcardproducts[0]['product_version_id'] = $product_version;
        }
        return $shoppingcardproducts;
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
        // $array = $request->session()->get('selectedProduct');
        $array[] = ['product_version_id' => $request->selectedProduct, 'size_id' => $request->selectedSize, 'ammount' => 1];
        $request->session()->put('selectedProduct', $array);

        return $request->session()->get('selectedProduct');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Sizes  $sizes
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return view('shoppingcard.index');
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
    { }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Sizes  $sizes
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        //
    }
}
