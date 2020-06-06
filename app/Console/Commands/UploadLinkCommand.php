<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UploadLinkCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'upload:link';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a symbolic link from "public/uploads" to "storage/app/public/uploads"';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (file_exists(public_path('uploads'))) {
            return $this->error('The "public/uploads" directory already exists.');
        }

        $this->laravel->make('files')->link(
            storage_path('app/public/uploads'), public_path('uploads')
        );

        $this->info('The [public/uploads] directory has been linked.');
    }
}
