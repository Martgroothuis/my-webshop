<?php

namespace App\Http\Controllers;

use App\Image;
use App\Product;
use App\ProductVersion;

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
        // return $request;
        foreach ($images as $image) {
            $this->SaveImage($image);
        }
        return redirect("/admin");
    }

    public function SaveImage($image)
    {
        $imageName = time() . '.' . 'webp';
        $img = Intervention::make($image)->fit(610, 610)->save('images/' . $imageName);
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
        $images = Image::with(['productVersion'])
            ->with(['productVersion.color', 'productVersion.product'])->get();
        return $images;
    }

    public function show(Product $image)
    {

        $productVersions = $image->productVersion;
        foreach ($productVersions as $productVersion) {
            $images[] = $productVersion->image;
        }

        $images = collect($images)->flatten();

        $i = 0;
        foreach ($images as $image) {
            $i++;
            $image['id'] = $i;
        }

        return $images;
    }

    public function update(Request $request, Image $image)
    {
        $image->update($request->all());

        return response()->json($image, 200);
    }

    public function destroy(Image $image)
    {
        // return $image->url;
        // return Intervention::canvas(800, 600, '#ccc');

        // Intervention::make('images/1572524828.jpg')->destroy();
        // Intervention::make('images/small/' . $image->url)->destroy();

        $image->delete();

        return response()->json(null, 204);
    }
}
