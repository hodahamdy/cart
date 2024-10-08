<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Facades\CartFacade;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $productId = $request->input('product_id');
        $userId = $request->input('user_id');
        $sizeId = $request->input('size_id');
        $colorId = $request->input('color_id');
        $quantity = $request->input('quantity');

        CartFacade::addToCart($productId, $sizeId, $colorId, $quantity,$userId);

        return response()->json(['message' => 'Product added to cart']);
    }

    public function removeFromCart(Request $request)
    {
        $productId = $request->input('product_id');
        $sizeId = $request->input('size_id');
        $userId = $request->input('user_id');
        $colorId = $request->input('color_id');

        CartFacade::removeFromCart($productId, $sizeId, $colorId,$userId);

        return response()->json(['message' => 'Product removed from cart']);
    }

    public function updateCart(Request $request)
    {
        $productId = $request->input('product_id');
        $userId = $request->input('user_id');
        $sizeId = $request->input('size_id');
        $colorId = $request->input('color_id');
        $quantity = $request->input('quantity');

        try {
            CartFacade::updateCart($productId, $sizeId, $colorId, $quantity, $userId);
            return response()->json(['message' => 'Cart updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function viewCart(Request $request)
    {
        $userId = $request->input('user_id');

        $cartItems = CartFacade::getCartContents($userId);
        return response()->json($cartItems);
    }
}
