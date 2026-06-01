<?php

namespace App\Console\Commands;

use App\Services\AdminNotificationsService;
use Illuminate\Console\Command;

class SendAdminNotificationsDigest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:notifications-digest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send 12-hour admin notification digest emails';

    /**
     * Execute the console command.
     */
    public function handle(AdminNotificationsService $service): int
    {
        try {
            $service->sendDigest();
            $this->info('Admin notification digest processed.');
            return self::SUCCESS;
        } catch (\Throwable $e) {
            // Keep command resilient and avoid throwing hard failures.
            $this->error('Admin notification digest failed unexpectedly: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
