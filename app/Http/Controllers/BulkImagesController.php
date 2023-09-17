<?php

namespace App\Http\Controllers;

use App\Models\BulkImage;
use Illuminate\Http\Request;
use Marvel\Database\Models\Attachment;
use Marvel\Database\Models\Product;
use Illuminate\Support\Facades\Log;

class BulkImagesController extends Controller
{

    public function bulk_images(Request $request)
    {
        $images = BulkImage::all();
        $imagesWithProductNames = [];

        foreach ($images as $image) {
            $product = Product::find($image->product_id);
            $imageWithProductName = [
                'id' => $image->id,
                'image_name' => $image->image_name,
                'image_path' => $image->image_path,
                'product_name' => $product->name,
                'product_slug' => $product->slug
            ];
            $imagesWithProductNames[] = $imageWithProductName;
        }

        return response()->json($imagesWithProductNames, 200);
    }



    public function store_bulk_images(Request $request)
    {
        $images = $request->allFiles();
        $productImageArrays = [];
        $urls = [];
        $chunkSize = 10;
        $chunks = array_chunk($images, $chunkSize);

        try {
            foreach ($chunks as $chunk) {
                foreach ($chunk as $image) {
                    $image_name = $image->getClientOriginalName();
                    $product_sku = explode('-', $image_name)[0];
                    $product = Product::where('sku', $product_sku)->first();
                    if(!$product) continue;
                    $uploadedImages[] = $product->name;

                    // store the attachment 
                    $bulk_image = new BulkImage();
                    $bulk_image->image_name = $image_name;
                    $bulk_image->product_id = $product->id;

                    // create attachment and thumbnail 
                    $attachment = new Attachment();
                    $attachment->save();
                    $attachment->addMedia($image)->toMediaCollection();

                    foreach ($attachment->getMedia() as $media) {
                        if (strpos($media->mime_type, 'image/') !== false) {
                            $converted_url = [
                                'thumbnail' => $media->getUrl('thumbnail'),
                                'original' => $media->getUrl(),
                                'id' => $attachment->id
                            ];
                        } else {
                            $converted_url = [
                                'thumbnail' => '',
                                'original' => $media->getUrl(),
                                'id' => $attachment->id
                            ];
                        }

                        $bulk_image->image_path = $converted_url['original'];
                        $bulk_image->thumbnail_path = $converted_url['thumbnail'];
                    }
                    $urls[] = $converted_url;
                    $product->image = $urls[0];
                    $product->save();

                    $productImageArrays[$product->id][] = $converted_url;

                    $bulk_image->save();
                }

                foreach ($productImageArrays as $productId => $imageArray) {
                    $product = Product::find($productId);
                    if(!$product) continue;
                    $product->gallery = (!empty($product->gallery)) ? array_merge($product->gallery, $imageArray) : $imageArray;
                    $product->save();
                }
            }
        } catch (\Exception $err) {
            Log::error($err);
            return response()->json(['message' => 'An error occurred. Please check the logs for more information.'], 500);
        }

        return response()->json($productImageArrays, 200);
    }

}
