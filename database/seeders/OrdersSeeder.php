<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\UserPurchasedItem;
use App\Models\Course;
use App\Models\Lesson;

class OrdersSeeder extends Seeder
{
    public function run(): void
    {
        $students = User::where('role', 'student')->get();

        $courses  = Course::all();
        $lessons  = Lesson::all();

        if ($students->isEmpty() || ($courses->isEmpty() && $lessons->isEmpty())) {
            $this->command->warn('No students or purchasable items found.');
            return;
        }

        foreach ($students as $student) {

            // Orders count per student
            $ordersCount = rand(5, 8);

            // Randomly fail 1–2 orders
            $failedIndexes = collect(range(0, $ordersCount - 1))
                ->shuffle()
                ->take(rand(1, 2))
                ->toArray();

            for ($i = 0; $i < $ordersCount; $i++) {

                $isFailed = in_array($i, $failedIndexes);

                $order = Order::create([
                    'user_id'          => $student->id,
                    'status'           => $isFailed ? 'failed' : 'completed',
                    'payment_method'   => collect(['paypal', 'stripe:card'])->random(),
                    'transaction_id'   => $isFailed ? null : strtoupper(Str::random(12)),
                    'payment_details'  => $isFailed ? null : json_encode([
                        'gateway' => 'sandbox',
                        'paid_at' => now(),
                    ]),
                    'note'             => $isFailed ? 'Payment failed (seeded)' : 'Order completed',
                    'is_active'        => !$isFailed,
                ]);

                // Random number of items per order
                $itemsCount = rand(1, 3);

                for ($j = 0; $j < $itemsCount; $j++) {

                    // Randomly pick course or lesson
                    $modelType = collect(['course', 'lesson'])->random();

                    if ($modelType === 'course' && $courses->isNotEmpty()) {
                        $item = $courses->random();
                        $modelClass = Course::class;
                    } elseif ($lessons->isNotEmpty()) {
                        $item = $lessons->random();
                        $modelClass = Lesson::class;
                    } else {
                        continue;
                    }

                    $price = rand(20, 150);
                    $tax   = round($price * 0.05, 2);
                    $total = $price + $tax;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'item_id'  => $item->id,
                        'model'    => $modelClass,
                        'quantity' => 1,
                        'price'    => $price,
                        'tax'      => $tax,
                        'total'    => $total,
                        'status'   => $isFailed ? 'failed' : 'completed',
                    ]);

                    // Only successful orders grant access
                    if (!$isFailed) {
                        UserPurchasedItem::firstOrCreate([
                            'user_id'           => $student->id,
                            'purchasable_id'   => $item->id,
                            'purchasable_type' => $modelClass,
                        ], [
                            'active' => true,
                        ]);
                    }
                }
            }
        }

        $this->command->info('Orders, order items, and purchases seeded successfully.');
    }
}
