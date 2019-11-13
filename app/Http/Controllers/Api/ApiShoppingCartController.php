<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\ProductVersion;
use App\Size;

use App\Http\Requests;


class ApiShoppingCartController extends Controller
{
    // use Session;

    public function index(Request $request)
    {
        $shoppingcardproducts = $request->session()->get('selectedProduct');

        if ($shoppingcardproducts) {
            $i = 0;
            foreach ($shoppingcardproducts as $shoppingcardproduct) {
                $shoppingcardproducts[$i]['product_version_id'] = ProductVersion::Where('id', '=', $shoppingcardproduct['product_version_id'])
                    ->with(array('image' => function ($query) {
                        $query->first();
                    }))
                    ->with(['color'])
                    ->with(['product'])
                    ->get()->first();

                $shoppingcardproducts[$i]['size_id'] = Size::Where('id', '=', $shoppingcardproduct['size_id'])->with(['waistSize'])->with(['lengthSize'])->get()->first();
                $i++;
            }
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
        $id = 0;

        $array = $request->session()->get('selectedProduct');
        if ($array) {
            foreach ($array as $item) {

                if ($item['product_version_id'] == $request->product_version_id and $item['size_id'] == $request->size_id) {
                    return 'same';
                }
            }
            $id = count($array);
        }


        $array[$id] = ['id' => $id, 'product_version_id' => $request->product_version_id, 'size_id' => $request->size_id, 'ammount' => 1];
        $request->session()->put('selectedProduct', $array);

        return $request->session()->get('selectedProduct');
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
    public function update($id, Request $request)
    {
        if ($request->ammount == 0) {
            $array = $request->session()->get('selectedProduct');
            unset($array[$id]);
            $request->session()->put('selectedProduct', $array);
            return $request->session()->get('selectedProduct');
        } else {
            $array = $request->session()->get('selectedProduct');
            $array[$id]['ammount'] = $request->ammount;
            $request->session()->put('selectedProduct', $array);
            return $request->session()->get('selectedProduct');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Sizes  $sizes
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $request->session()->forget('selectedProduct');
        // Session::forget('selectedProduct');

        return $request->session()->get('selectedProduct');
    }
}
