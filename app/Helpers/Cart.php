<?php

namespace App\Helpers;

use App\Models\CartItem;
use App\Models\Product;
Use Illuminate\Support\Arr;

/**
 * Class Cart
 * 
 * 
 * @package App\Helpers
 */
class Cart
{

    public static function getCartItemsCount()
    {
        $request = \request();
        $user = $request->user();
        if ($user) {
            return CartItem::where('user_id', $user->id)->sum('quantity');
        } else {
            $cartItems = self::getCookieCartItems();
            $cartItemsCount = array_reduce($cartItems, fn ($carry, $item) => $carry + $item['quantity']);
            return isset($cartItemsCount) ? $cartItemsCount : 0;
        }
    }

    public static function getCartItems()
    {
        $request = \request();
        $user = $request->user();
        if ($user) {
            return CartItem::where('user_id', $user->id)->get()->map(
                fn ($item) => ['product_id' => $item->product_id, 'quantity' => $item->quantity]
            );
        } else {
            return self::getCookieCartItems();
        }
    }

    public static function getCookieCartItems()
    {
        $request = \request();

        return json_decode($request->cookie('cart_items', '[]'), true);
    }

    public static function getCountFromItems($cartItems)
    {
        return array_reduce($cartItems, fn ($carry, $item) => $carry + $item['quantity']);
    }

    public static function moveCartItemsInDb()
    {
        $request = \request();

        $cartItems = self::getCookieCartItems();

        $dbCartItems = CartItem::where(['user_id' => $request->user()->id])->get()->KeyBy('product_id');
        // KeyBy ---> Get all cart items and index them by key as product_id
        $newCartItems = [];
        foreach ($cartItems as $cartItem) {
            if (isset($dbCartItems[$cartItem['product_id']])) {
                continue;
            } else {
                $newCartItems[] = [
                    'user_id' => $request->user()->id,
                    'product_id' => $cartItem['product_id'],
                    'quantity' => $cartItem['quantity']
                ];
            }
        }

        if (!empty($newCartItems)) {
            CartItem::insert($newCartItems);
        }
    }

    public static function getProductsAndCartItems() {
        $cartItems = Self::getCartItems();
        $ids = Arr::pluck($cartItems,'product_id');
        $products = Product::query()->whereIn('id', $ids)->get();
        $cartItems = Arr::keyBy($cartItems,'product_id');

        return [$products, $cartItems];
    }
}
