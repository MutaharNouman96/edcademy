<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Services\OrderService;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }


    public function addToOrderCart(Request $request)
    {
        $request->validate([
            'item_id' => 'required',
            'model' => 'required',
        ]);
        $response = app(OrderService::class)->addOrderItemToLatestCartOrder($request->all());
        if ($request->has('responseType') && $request->responseType == 'json') {
            return response()->json([
                'status' => $response,
                'message' => Session::get('message'),
            ]);
        }

        return back();
    }

    public function buyNow() {}

    public function removeOrderItem(Request $request) {
        $orderItem = OrderItem::find($request->item_id);
        if (!$orderItem) {
            return back()->with('error', 'Order item not found.');
        }
        if($orderItem->order->user_id != auth()->id()) {
            return back()->with('error', 'You are not authorized to remove this order item.');
        }

        $response = app(OrderService::class)->removeOrderItem($orderItem);
        if ($request->has('responseType') && $request->responseType == 'json') {
            return response()->json([
                'status' => $response,
                'message' => Session::get('message'),
            ]);
        }
        return back();
    }


    public function success(Order $order)
    {
        // Security: only owner can view
        abort_if(auth()->id() != $order->user_id, 403);

        $order->load('items'); // adjust relation names

        return view('website.payment-success', compact('order'));
    }
}
