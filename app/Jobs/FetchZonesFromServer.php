<?php

namespace App\Jobs;

use App\DomainCheck\DomainCheck;
use App\Models\Server;
use App\Models\Zone;
use DateTime;
use Illuminate\Support\Facades\Crypt;
use mysqli;

class FetchZonesFromServer extends Job {
    protected Server $server;

    /**
     * Create a new job instance.
     *
     * @param Server $server
     */
    public function __construct(Server $server) {
        $this->server = $server;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        $connection         = new mysqli($this->server->db_hostname, $this->server->db_username,
            Crypt::decrypt($this->server->db_password), $this->server->db_database);
        $dbDomainNameColumn = $this->server->db_domain_name_column;
        $dbTable            = $this->server->db_table;

        $domains = [];
        $results = $connection->query("SELECT `$dbDomainNameColumn` FROM `$dbTable` GROUP BY `$dbDomainNameColumn`");

        if ($results->num_rows > 0) {
            while ($row = $results->fetch_assoc()) {
                $domains[] = $row[$dbDomainNameColumn];
            }
        }

        foreach ($domains as $domain) {
            if ( ! DomainCheck::instance()->isDomain($domain)) {
                continue;
            }
            $zone = Zone::where('domain', '=', $domain)->first();
            if ($zone !== null) {
                $zone->last_seen = new DateTime();
                $zone->save();
                continue;
            }
            Zone::create([
                'domain'      => $domain,
                'ip'          => $this->server->ip,
                'file_exists' => false,
                'last_seen'   => new DateTime(),
            ]);
        }

        $connection->close();
    }
}
