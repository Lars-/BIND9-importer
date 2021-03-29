<?php

namespace App\Console\Commands;

use App\Models\Zone;
use DateTime;
use Illuminate\Console\Command;

class ShowAllZones extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ljpc:zones';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show a list of all domains in the database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $zones = Zone::all();
        $data  = [];
        foreach ($zones as $zone) {
            $daysToRetainZoneFile = env('BIND9_DAYS_TO_KEEP_REMOVED_ZONES', 14);
            if ((new DateTime($zone->last_seen))->diff(new DateTime)->days > $daysToRetainZoneFile) {
                continue;
            }

            $data[] = [
                $zone->domain,
                $zone->ip,
                $zone->isAvailableOnServer() ? 'slave' : 'master',
                $zone->last_seen,
                $zone->created_at,
            ];
        }

        $this->table([
            'Domain',
            'Server IP',
            'Mode',
            'Last seen',
            'Created at',
        ], $data);
    }
}
