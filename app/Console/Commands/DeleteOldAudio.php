<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;



class DeleteOldAudio extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-old-audio';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $files = Storage::disk('public')->files('audio');
        $deletedCount = 0;

        foreach ($files as $file) {
            //Gets and changes time stamp into carbon date for comparison
            $lastModified = Carbon::createFromTimestamp(Storage::disk('public')->lastModified($file));

            //If file is older than 30 minutes
            if ($lastModified->lt(now()->subMinutes(30))){
                //Delete file from public directory
                Storage::disk('public')->delete($file);
                $deletedCount++;
            }
        }

    }
}
