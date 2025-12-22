<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class OrderService
{
    public function createOrUpdateAndReturnCartOrder()
    {
        // Determine the identifier: use Auth ID if logged in, otherwise the Session ID
        $identifier = get_cart_identifier();

        // Find the order by this identifier or create a new instance
        $order = Order::firstOrNew(['user_id' => $identifier, 'status' => 'cart']);

        $order->save();

        return $order;
    }

    public function migrateSessionCartToUser()
    {
        $sessionId = Cookie::get('guest_cart_id');
        $userId = Auth::id();
        // dd($userId, $sessionId);

        if (!$userId) {
            return;
        }

        // Find orders belonging to the session with status 'cart'
        Order::where('user_id', $sessionId)
            ->where('status', 'cart')
            ->update(['user_id' => $userId]);
    }

    public function addOrderItemToLatestCartOrder(array $orderItemsData): bool
    {
        try {
            $order = $this->createOrUpdateAndReturnCartOrder();

            if (!$order) {
                return false;
            }



            if ($order) {

                if(OrderItem::where('order_id', $order->id)->where('item_id', $orderItemsData['item_id'])->where('model', $orderItemsData['model'])->exists()) {
                    Session::flash('message', 'Item already exists in cart.');
                    return false;
                }
                $orderItemsData['order_id'] = $order->id;
                $orderItemsData['price'] = $this->getItemPrice($orderItemsData['item_id'], $orderItemsData['model']);
                $orderItemsData['quantity'] = 1;
                $orderItemsData['total'] = $orderItemsData['price'] * $orderItemsData['quantity'];
                $order->items()->create($orderItemsData);
                Session::flash('message', 'Item added to cart successfully.');
            }

            return true;
        } catch (\Exception $e) {
            // Handle the exception
            Session::flash('message', $e->getMessage());
            return false;
        }
    }

    public function removeOrderItem(OrderItem $orderItem): bool
    {
        $orderItem->delete();

        return true;
    }

    public function getItemPrice($item_id, $model)
    {
        $item = $model::find($item_id);
        return $item->price ?? 0;
    }
}
