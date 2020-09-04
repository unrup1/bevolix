<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteGuestData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'guests:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear guest data which is older then 30 days';

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
     * @return int
     */
    public function handle()
    {
        DB::table('guests')
            ->where('created_at', '<', DB::raw('DATE_SUB(NOW(), INTERVAL 30 DAY)'))
            ->delete();
    }
}
