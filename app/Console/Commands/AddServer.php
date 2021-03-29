<?php

namespace App\Console\Commands;

use App\Models\Server;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;
use mysqli;

class AddServer extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ljpc:add-server';
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
        $ip                 = $this->ask('Source DNS IP address');
        $dbHostname         = $this->ask('DB hostname');
        $dbUsername         = $this->ask('DB username', 'administrator');
        $dbPassword         = Crypt::encrypt($this->secret('DB password'));
        $dbDatabase         = $this->ask('DB database', 'psa');
        $dbTable            = $this->ask('DB table', 'dns_zone');
        $dbDomainNameColumn = $this->ask('DB domain name column', 'name');

        try {
            $conn = new mysqli($dbHostname, $dbUsername, Crypt::decrypt($dbPassword), $dbDatabase);
            $conn->query("SELECT `$dbDomainNameColumn` FROM `$dbTable` GROUP BY `$dbDomainNameColumn`");

            $conn->close();
        } catch (Exception $exception) {
            $this->error('Could not connect to the database or fetch domains from the table. Error:' . $exception->getmessage());

            return;
        }

        $data = Server::create([
            'ip'                    => $ip,
            'db_hostname'           => $dbHostname,
            'db_username'           => $dbUsername,
            'db_password'           => $dbPassword,
            'db_database'           => $dbDatabase,
            'db_table'              => $dbTable,
            'db_domain_name_column' => $dbDomainNameColumn,
        ]);

        echo 'Server ' . $data->id . ' added' . "\n";
    }
}
