<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payout;
use App\Models\User;
use App\Models\Earning;
use Illuminate\Support\Str;

class PayoutSeeder extends Seeder
{
    public function run()
    {
        $educators = User::where('role', 'educator')->pluck('id')->toArray();
        $earnings  = Earning::pluck('id')->toArray();

        if (empty($educators)) {
            dd("No educators found — seed users first.");
        }

        // Generate 100 payout records
        for ($i = 0; $i < 100; $i++) {

            $educatorId = $educators[array_rand($educators)];

            // Link to an earning optionally
            $earningId = collect([null, $earnings[array_rand($earnings)] ?? null])->random();

            $amount = rand(5000, 50000) / 100; // 50–500

            $status = collect(['pending', 'processing', 'completed', 'cancelled'])->random();

            Payout::create([
                'educator_id' => $educatorId,
                'amount'      => $amount,
                'status'      => $status,
                'processed_at' => in_array($status, ['processing', 'completed'])
                    ? now()->subDays(rand(1, 20))
                    : null,
                'processed_by' => in_array($status, ['processing', 'completed'])
                    ? 'system-admin'
                    : null,
                'description' => Str::random(50),
                'earning_id'  => $earningId,
            ]);
        }
    }
}
