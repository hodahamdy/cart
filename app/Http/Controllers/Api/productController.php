<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\ProductSize;
use App\Models\ProductColor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class productController extends Controller
{
    //
    public function index(){
        $products= Product::with('colors','sizes')->get();
        return response()->json($products);

    }
    public function store(Request $request)
    {
        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'colors' => 'required|string', // Validate colors as a comma-separated string
            'sizes' => 'required|string',  // Validate sizes as a comma-separated string
        ]);

        // If validation fails, return error
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Create the product
        $product = new Product();
        $product->title = $request->input('title');
        $product->price = $request->input('price');
        $product->quantity = $request->input('quantity');
        $product->save();

        // Handle product colors
        if ($request->filled('colors')) {
            // Split the comma-separated colors string into an array
            $colors = explode(',', $request->input('colors'));
            foreach ($colors as $color) {
                // Trim whitespace and create a new Color instance and save it
                $productColor = new ProductColor();
                $productColor->product_id = $product->id;
                $productColor->color = trim($color);
                $productColor->save();
            }
        }

        // Handle product sizes
        if ($request->filled('sizes')) {
            // Split the comma-separated sizes string into an array
            $sizes = explode(',', $request->input('sizes'));
            foreach ($sizes as $size) {
                // Trim whitespace and create a new ProductSize instance and save it
                $productSize = new ProductSize();
                $productSize->product_id = $product->id;
                $productSize->size = trim($size);
                $productSize->save();
            }
        }

        // Return a success message with 201 Created status
        return response()->json(['message' => 'Product created successfully'], 201);
    }
    public function update(Request $request, $id)
    {
        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric',
            'quantity' => 'sometimes|required|integer',
            'colors' => 'sometimes|required|string', // Validate colors as a comma-separated string
            'sizes' => 'sometimes|required|string',  // Validate sizes as a comma-separated string
        ]);

        // If validation fails, return error
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Find the product by id
        $product = Product::find($id);

        // Check if the product exists
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Update product fields only if they exist in the request
        if ($request->has('title')) {
            $product->title = $request->input('title');
        }
        if ($request->has('price')) {
            $product->price = $request->input('price');
        }
        if ($request->has('quantity')) {
            $product->quantity = $request->input('quantity');
        }
        $product->save();

        // Update product colors
        if ($request->filled('colors')) {
            // Remove existing colors
            $product->colors()->delete();

            // Split the comma-separated colors string into an array
            $colors = explode(',', $request->input('colors'));
            foreach ($colors as $color) {
                // Trim whitespace and create a new Color instance and save it
                $productColor = new ProductColor();
                $productColor->product_id = $product->id;
                $productColor->color = trim($color);
                $productColor->save();
            }
        }

        // Update product sizes
        if ($request->filled('sizes')) {
            // Remove existing sizes
            $product->sizes()->delete();

            // Split the comma-separated sizes string into an array
            $sizes = explode(',', $request->input('sizes'));
            foreach ($sizes as $size) {
                // Trim whitespace and create a new ProductSize instance and save it
                $productSize = new ProductSize();
                $productSize->product_id = $product->id;
                $productSize->size = trim($size);
                $productSize->save();
            }
        }

        // Return a success message with 200 OK status
        return response()->json(['message' => 'Product updated successfully'], 200);
    }
    public function delete($id)
    {
        // Find the product by id
        $product = Product::find($id);

        // Check if the product exists
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Delete associated colors and sizes
        $product->colors()->delete();
        $product->sizes()->delete();

        // Delete the product
        $product->delete();

        // Return a success message
        return response()->json(['message' => 'Product deleted successfully'], 200);
    }
}

