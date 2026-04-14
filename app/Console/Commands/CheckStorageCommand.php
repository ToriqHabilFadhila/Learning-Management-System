<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AdminNotificationService;

class CheckStorageCommand extends Command
{
    protected $signature = 'admin:check-storage';
    protected $description = 'Check storage usage and notify admin if running low';

    public function handle()
    {
        $this->info('Checking storage usage...');
        
        AdminNotificationService::checkStorageWarning();
        
        $this->info('Storage check completed!');
        return 0;
    }
}
