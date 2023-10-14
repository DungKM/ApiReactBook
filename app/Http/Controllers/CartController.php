<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Cart;
use App\Models\Cart_Order;
use App\Models\Order;
use App\Models\Product;

class CartController extends Controller
{
    protected $cart;
    protected $product;

    public function __construct(Cart $cart, Product $product)
    {
        $this->cart = $cart;
        $this->product = $product;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $user_id)
    {
        $carts = $this->cart->where('user_id', $user_id)->with(['products', 'user'])->latest('id')->get();
        return response()->json($carts);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $dataCreate = $request->all();
        $user_id = $dataCreate['user_id'];
        $product_id = $dataCreate['product_id'];

        // Kiểm tra xem một mục giỏ hàng với cùng user_id và product_id đã tồn tại chưa
        $existingCart = $this->cart
            ->where('user_id', $user_id)
            ->where('product_id', $product_id)
            ->first();

        if ($existingCart) {
            // Nếu mục đã tồn tại, hãy cập nhật số lượng
            $existingCart->increment('quantity', $dataCreate['quantity']);
        } else {
            // Nếu mục không tồn tại, hãy tạo một mục giỏ hàng mới
            $cart = $this->cart->create($dataCreate);
        }
        return response()->json([
            'message' => 'Tạo thành công'
        ]);
    }
    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cart $cart)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cart $cart)
    {
        // Kiểm tra xem giỏ hàng có tồn tại không
        if (!$cart) {
            return response()->json(['message' => 'Giỏ hàng không tồn tại.'], 404);
        }

        // Lấy số lượng mới từ yêu cầu
        $newQuantity = $request->input('quantity');

        // Cập nhật số lượng sản phẩm trong giỏ hàng
        $cart->quantity = $newQuantity;
        $cart->update();

        return response()->json([
            'cart' => $cart,
            'message' => 'Cập nhật số lượng thành công.'
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        if (!$cart) {
            return response()->json(['message' => 'Giỏ hàng không tồn tại.'], 404);
        }

        // Xóa giỏ hàng khỏi cơ sở dữ liệu
        $cart->delete();

        return response()->json(['message' => 'Xóa giỏ hàng thành công.']);
    }
    /**
     * Xóa toàn bộ giỏ hàng của người dùng dựa trên user_id.
     */
    public function destroyByUserId($user_id)
    {
        // Tìm tất cả các mục giỏ hàng của người dùng dựa trên user_id
        $carts = $this->cart->where('user_id', $user_id)->get();

        if ($carts->isEmpty()) {
            return response()->json(['message' => 'Không tìm thấy giỏ hàng cho người dùng.'], 404);
        }

        $carts->each->delete();

        return response()->json(['message' => 'Xóa toàn bộ giỏ hàng thành công.']);
    }


    public function createOrder(Request $request)
    {
        $user_id = $request->input('user_id');

        $carts = Cart::where('user_id', $user_id)->get();

        if ($carts->isEmpty()) {
            return response()->json(['message' => 'Không có sản phẩm trong giỏ hàng.'], 400);
        }

        $order = Order::create([
            'status' => 'pending',
            'customer_name' => $request->input('customer_name'),
            'customer_email' => $request->input('customer_email'),
            'customer_phone' => $request->input('customer_phone'),
            'customer_address' => $request->input('customer_address'),
            'payment' => $request->input('payment'),
            'total' => $request->input('total'),
            'user_id' => $user_id,
        ]);


        foreach ($carts as $cart) {
            if ($cart->products->sale > 0) {
                $price = $cart->products->price - $cart->products->sale;
            } else {
                $price = $cart->products->price;
            }
            Cart_Order::create([
                'order_id' => $order->id,
                'product_id' =>  $cart->products->id,
                'quantity' => $cart->quantity,
                'product_name' => $cart->products->name,
                'product_image' => $cart->products->image,
                'product_price' => $price,
            ]);
            $product = Product::where('id', $cart->products->id)->first();
            $product->decrement('quantity', $cart->quantity);
        }
        $this->destroyByUserId($user_id);

        return response()->json(['message' => 'Đặt hàng thành công.']);
    }
    
    public function getOrdersByUserId($user_id)
    {
        $orders = Order::where('user_id', $user_id)->with('cart_orders')->get();

        if ($orders->isEmpty()) {
            return response()->json(['message' => 'No orders found for this user.'], 404);
        }

        return response()->json($orders);
    }
}