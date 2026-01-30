<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use App\Models\CourseSection;

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

                if (OrderItem::where('order_id', $order->id)->where('item_id', $orderItemsData['item_id'])->where('model', $orderItemsData['model'])->exists()) {
                    Session::flash('message', 'Item already exists in cart.');
                    return false;
                }
                $orderItemsData['order_id'] = $order->id;
                if ($orderItemsData['model'] == 'App\Models\Section') {
                    $section = CourseSection::find($orderItemsData['item_id']);
                    $lessons = $section->lessons;

                    foreach ($lessons as $lesson) {
                        $lessonData = $orderItemsData;
                        $lessonData['item_id'] = $lesson->id;
                        $lessonData['model'] = 'App\Models\Lesson';
                        $lessonData['price'] = $this->getItemPrice($lesson->id, 'App\Models\Lesson');
                        $lessonData['quantity'] = 1;
                        $lessonData['tax'] = setting('tax', 0);

                        $total = $lessonData['price'] * $lessonData['quantity'];
                        $totalWithTax = $total + ($total * ($lessonData['tax'] / 100));
                        $lessonData['total'] = $totalWithTax;

                        $order->items()->create($lessonData);
                    }
                    return true;
                } else {
                    $orderItemsData['price'] = $this->getItemPrice($orderItemsData['item_id'], $orderItemsData['model']);
                    $orderItemsData['quantity'] = 1;
                    $orderItemdsData['tax'] = setting('tax', 0);

                    $total = $orderItemsData['price'] * $orderItemsData['quantity'];
                    $totalWithTax = $total + ($total * ($orderItemdsData['tax'] / 100));
                    $orderItemsData['total'] = $totalWithTax;

                    $order->items()->create($orderItemsData);
                }
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
