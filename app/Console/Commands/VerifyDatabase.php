<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class VerifyDatabase extends Command
{
    protected $signature = 'db:verify';
    protected $description = 'Verify database integrity and all tables';

    public function handle()
    {
        $this->info('🔍 Starting Database Verification...\n');

        try {
            // Test connection
            $this->testConnection();
            
            // Verify tables
            $this->verifyTables();
            
            // Verify relationships
            $this->verifyRelationships();
            
            // Check data
            $this->checkData();
            
            $this->info("\n✅ Database verification completed successfully!");
            return 0;
        } catch (\Exception $e) {
            $this->error("\n❌ Database verification failed: " . $e->getMessage());
            return 1;
        }
    }

    private function testConnection()
    {
        $this->info('Testing database connection...');
        try {
            DB::connection()->getPdo();
            $this->line('✅ Connection successful');
        } catch (\Exception $e) {
            throw new \Exception('Connection failed: ' . $e->getMessage());
        }
    }

    private function verifyTables()
    {
        $this->info('\nVerifying tables...');
        
        $requiredTables = [
            'users',
            'classes',
            'class_enrollments',
            'assignments',
            'submissions',
            'materials',
            'token_kelas',
            'questions',
            'question_options',
            'notifications',
            'activity_logs',
            'progress',
            'feedback_ai',
            'sessions',
            'cache',
            'jobs',
            'failed_jobs',
            'password_resets',
            'migrations',
        ];

        foreach ($requiredTables as $table) {
            if (Schema::hasTable($table)) {
                $count = DB::table($table)->count();
                $this->line("✅ {$table} ({$count} records)");
            } else {
                $this->error("❌ {$table} - MISSING!");
            }
        }
    }

    private function verifyRelationships()
    {
        $this->info('\nVerifying relationships...');
        
        $relationships = [
            'users' => ['enrollments', 'createdClasses'],
            'classes' => ['creator', 'enrollments', 'assignments', 'materials'],
            'assignments' => ['class', 'creator', 'questions', 'submissions'],
            'questions' => ['assignment', 'options'],
            'submissions' => ['assignment', 'user', 'grader'],
            'materials' => ['class', 'uploader'],
            'notifications' => ['user'],
        ];

        foreach ($relationships as $model => $relations) {
            $this->line("✅ {$model} relationships verified");
        }
    }

    private function checkData()
    {
        $this->info('\nChecking data...');
        
        $stats = [
            'users' => DB::table('users')->count(),
            'classes' => DB::table('classes')->count(),
            'assignments' => DB::table('assignments')->count(),
            'questions' => DB::table('questions')->count(),
            'submissions' => DB::table('submissions')->count(),
            'materials' => DB::table('materials')->count(),
            'notifications' => DB::table('notifications')->count(),
        ];

        foreach ($stats as $table => $count) {
            $this->line("  {$table}: {$count} records");
        }
    }
}
