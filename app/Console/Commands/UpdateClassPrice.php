<?php

namespace App\Console\Commands;

use App\Models\Master\Grade;
use Illuminate\Console\Command;
use App\Models\Master\ClassPrice;
use App\Models\Master\MasterClass;

class UpdateClassPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:class-price';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Class Price';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $classes = MasterClass::withoutGlobalScopes()->where('client_id', 1)->get();
        foreach ($classes as $key => $class) {
            $grades = Grade::withoutGlobalScopes()->where('client_id', $class->client_id)->get();
            foreach ($grades as $key => $grade) {
                ClassPrice::insert([
                    ['client_id' => $class->client_id, 'class_id' => $class->id, 'grade_id' => $grade->id, 'price' => 0, 'created_at' => now(), 'updated_at' => now()],
                ]);
            }
        }

        $this->line(' has been Updated!');
        return true;
    }
}
