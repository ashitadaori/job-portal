<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CreateBackupJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Generate backup filename
            $filename = 'backup_' . Carbon::now()->format('Y-m-d_H-i-s') . '.sql';
            
            // Create backup directory if it doesn't exist
            Storage::disk('local')->makeDirectory('backups');
            
            // Get database configuration
            $database = config('database.connections.mysql.database');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            
            // Generate backup file path
            $backupPath = storage_path('app/backups/' . $filename);
            
            // Create backup command
            $command = sprintf(
                'mysqldump -u%s -p%s %s > %s',
                $username,
                $password,
                $database,
                $backupPath
            );
            
            // Execute backup command
            exec($command);
            
            // Get backup file size
            $size = Storage::disk('local')->size('backups/' . $filename);
            
            // Record backup in database
            DB::table('backups')->insert([
                'filename' => $filename,
                'disk' => 'local',
                'size' => $size,
                'is_successful' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            // Keep only last 5 backups
            $oldBackups = DB::table('backups')
                ->orderBy('created_at', 'desc')
                ->skip(5)
                ->take(100)
                ->get();
                
            foreach ($oldBackups as $backup) {
                // Delete file
                Storage::disk($backup->disk)->delete('backups/' . $backup->filename);
                
                // Delete record
                DB::table('backups')->where('id', $backup->id)->delete();
            }
            
        } catch (\Exception $e) {
            // Log error
            DB::table('backups')->insert([
                'filename' => $filename ?? 'backup_failed',
                'disk' => 'local',
                'size' => 0,
                'is_successful' => false,
                'error_message' => $e->getMessage(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            throw $e;
        }
    }
} 