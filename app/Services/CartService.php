<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;

class CartService
{
    private static $instance = null;
    public $cartItems = [];

    private function __construct() {}

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new CartService();
        }
        return self::$instance;
    }

    public function addProduct($productId, $sizeId, $colorId, $quantity,$userId)
    {
        $price = $this->getProductPrice($productId);

        $this->cartItems[] = [
            'product_id' => $productId,
            'user_id' => $userId,
            'size_id' => $sizeId,
            'color_id' => $colorId,
            'quantity' => $quantity,
            'price' => $price,
            'subtotal' => $quantity * $price,
        ];
        // Save the product to the carts table in the database
        Cart::create([
            'product_id' => $productId,
            'user_id' => $userId,
            'size_id' => $sizeId,
            'color_id' => $colorId,
            'quantity' => $quantity,
            'price' => $price,
            'sub_total' => $quantity * $price,
        ]);
    }

    public function updateProduct($productId, $sizeId, $colorId, $quantity, $userId)
    {
        // Find the cart item in the database based on product_id, size_id, color_id, and user_id
        $cartItem = Cart::where([
            ['product_id', '=', $productId],
            ['size_id', '=', $sizeId],
            ['color_id', '=', $colorId],
            ['user_id', '=', $userId]
        ])->first();

        if ($cartItem) {
            // Update the quantity and subtotal in the database
            $cartItem->quantity = $quantity;
            $cartItem->sub_total = $quantity * $cartItem->price;
            $cartItem->save();

            // Update the in-memory cartItems array (optional)
            foreach ($this->cartItems as &$item) {
                if ($item['product_id'] == $productId && $item['size_id'] == $sizeId && $item['color_id'] == $colorId && $item['user_id'] == $userId) {
                    $item['quantity'] = $quantity;
                    $item['subtotal'] = $quantity * $item['price'];
                    break;
                }
            }

            return true;
        } else {
            throw new \Exception('Cart item not found');
        }
    }

    public function removeProduct($productId, $sizeId, $colorId,$userId)
    {
        $this->cartItems = array_filter($this->cartItems, function ($item) use ($productId, $sizeId, $colorId,$userId) {
            return !($item['product_id'] == $productId &&$item['size_id'] == $sizeId && $item['color_id'] == $colorId && $item['user_id'] == $userId);
        });
    }


    public function getCartItems($userId)
    {
        // Fetch from the database where user_id is the given userId
        return Cart::where('user_id', $userId)->get();
    }


    private function getProductPrice($productId)
    {

        $product = Product::find($productId);
        if ($product) {
            return $product->price;
        }
        throw new \Exception('Product not found');
    }
}
