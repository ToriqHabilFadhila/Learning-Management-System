<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AdminNotificationService;

class SendDailyStatsCommand extends Command
{
    protected $signature = 'admin:send-daily-stats';
    protected $description = 'Send daily statistics to admin';

    public function handle()
    {
        $this->info('Sending daily statistics to admin...');
        
        AdminNotificationService::sendDailyStats();
        
        $this->info('Daily statistics sent successfully!');
        return 0;
    }
}
