<?php

namespace App\Http\Controllers;

use App\Image;
use App\Product;
use App\Product_version;

use App\Traits\UploadTrait;
use Faker\Provider\Image as FakerImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use Intervention\Image\Facades\Image as Intervention;


use Illuminate\Http\Request;

class ImageController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function UploadImage(Request $request)
    {
        // $validatedData = $request->validate([
        //     'image' => 'image|mimes:jpeg,bmp,png|max:6000'
        // ]);

        $images = $request->file('image');
        return $request;
        foreach ($images as $image) {
            $this->SaveImage($image);
        }
        return redirect("/admin");
    }

    public function SaveImage($image)
    {
        $imageName = time() . '.' . 'jpg';
        $img = Intervention::make($image)->fit(1000, 1000)->save('images/' . $imageName);
        Intervention::make($image)->fit(100, 100)->save('images/small/' . $imageName);

        Image::create(['url' => $imageName]);
    }

    public function uploadOne(UploadedFile $uploadedFile, $folder = null, $disk = 'public', $filename = null)
    {
        $name = !is_null($filename) ? $filename : str_random(25);

        $file = $uploadedFile->storeAs($folder, $name . '.' . $uploadedFile->getClientOriginalExtension(), $disk);

        return $file;
    }

    public function index()
    {

        $images = Image::all();
        foreach ($images as $image) {
            if ($image->Product_version) {
                $image->Product_version->color->name;
                $image->Product_version->product->name;
            }
        }
        return $images;
    }
    public function show(Product $image)
    {
        $product_versions = $image->Product_version;
        foreach ($product_versions as $product_version) {
            $images[] = $product_version->image;
        }

        $images = collect($images)->flatten();

        $i = 0;
        foreach ($images as $image) {
            $i++;
            $image['id'] = $i;
        }

        return $images;
    }
    public function destroy(Image $image)
    {
        // return $image->url;
        Intervention::make('images/' . $image->url)->destroy();
        Intervention::make('images/small/' . $image->url)->destroy();

        $image->delete();

        return response()->json(null, 204);
    }
}
