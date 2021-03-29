<?php

namespace App\Console\Commands;

use App\Jobs\CreateBindFile;
use App\Jobs\FetchZonesFromServer;
use App\Models\Server;
use Illuminate\Console\Command;
use Throwable;

class FetchZones extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ljpc:fetch-zones';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $servers = Server::all();

        foreach ($servers as $server) {
            try {
                dispatch(new FetchZonesFromServer($server));
            } catch (Throwable $exception) {
                $this->error($exception->getMessage());
            }
        }

        dispatch(new CreateBindFile());
    }
}
