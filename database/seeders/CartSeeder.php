<?php

namespace Database\Seeders;
use App\Models\Cart;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    public function run()
    {
        // Lấy danh sách người dùng và sản phẩm
        $users = User::all();
        $products = Product::all();

        // Lặp qua danh sách người dùng và thêm các bản ghi giả mạo vào bảng carts
        foreach ($users as $user) {
            // Lấy ngẫu nhiên một số lượng sản phẩm
            $cartItemsCount = rand(1, 5);

            for ($i = 0; $i < $cartItemsCount; $i++) { // Lấy ngẫu nhiên một sản phẩm $product=$products->random();
                $product = $products->random();
                // Tạo bản ghi giả mạo trong bảng carts
                Cart::create([
                    'quantity' => rand(1, 10),
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                ]);
            }
        }
    }
}