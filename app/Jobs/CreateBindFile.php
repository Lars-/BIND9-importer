<?php

namespace App\Jobs;

use App\Models\Zone;

class CreateBindFile extends Job {
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct() {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        $fileContent = '';
        foreach (Zone::all() as $zone) {
            $fileContent .= $zone->zoneFilePart();
        }

        file_put_contents(storage_path('zonefile.db'), $fileContent);
    }
}
