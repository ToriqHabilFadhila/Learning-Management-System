<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CacheService;

class ClearLmsCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:clear-lms {--type=all : Type of cache to clear (all, recommendations, materials, feedback)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear LMS-specific cache from Redis';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->option('type');

        try {
            switch ($type) {
                case 'all':
                    CacheService::clearAll();
                    $this->info('✓ All LMS cache cleared successfully');
                    break;
                case 'recommendations':
                    $this->info('Use cache:clear-lms --type=all to clear all recommendations');
                    break;
                case 'materials':
                    $this->info('Use cache:clear-lms --type=all to clear all materials cache');
                    break;
                case 'feedback':
                    $this->info('Use cache:clear-lms --type=all to clear all feedback cache');
                    break;
                default:
                    $this->error('Invalid cache type. Use: all, recommendations, materials, or feedback');
                    return 1;
            }

            return 0;
        } catch (\Exception $e) {
            $this->error('Error clearing cache: ' . $e->getMessage());
            return 1;
        }
    }
}
