<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use App\Services\CartService;

class CartFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return CartService::getInstance();
    }


    public static function addToCart($productId, $sizeId, $colorId, $quantity,$userId)
    {
        return CartService::getInstance()->addProduct($productId, $sizeId, $colorId, $quantity,userId: $userId,);
    }

    public static function updateCart($productId, $sizeId, $colorId, $quantity, $userId)
    {
        return CartService::getInstance()->updateProduct($productId, $sizeId, $colorId, $quantity, $userId);
    }
    public static function removeFromCart($productId, $sizeId, $colorId,$userId)
    {
        return CartService::getInstance()->removeProduct($productId, $sizeId, $colorId,  $userId);
    }

    public static function getCartContents($userId)
    {
        return CartService::getInstance()->getCartItems($userId);
    }
}
