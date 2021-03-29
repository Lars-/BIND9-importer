<?php

namespace App\Models;

use DateTime;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Zone
 *
 * @property int $id
 * @property string $domain
 * @property string $ip
 * @property string $last_seen
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Zone newModelQuery()
 * @method static Builder|Zone newQuery()
 * @method static Builder|Zone query()
 * @method static Builder|Zone whereCreatedAt($value)
 * @method static Builder|Zone whereDomain($value)
 * @method static Builder|Zone whereId($value)
 * @method static Builder|Zone whereIp($value)
 * @method static Builder|Zone whereLastSeen($value)
 * @method static Builder|Zone whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Zone extends Model {
    protected $fillable = [
        'domain',
        'ip',
        'last_seen',
    ];

    public function zoneFilePart(): string {
        $domain = $this->domain;
        $ip     = $this->ip;

        $zoneFolder           = rtrim(trim(env('BIND9_ZONE_FILE_DIRECTORY')), '/');
        $daysToRetainZoneFile = env('BIND9_DAYS_TO_KEEP_REMOVED_ZONES', 14);
        if ((new DateTime($this->last_seen))->diff(new DateTime)->days > $daysToRetainZoneFile) {
            return '';
        }

        $availableOnServer = $this->isAvailableOnServer();
        $dbFileExists      = file_exists($zoneFolder . "/db.$domain");

        if ($availableOnServer) {
            return <<<LJPC
zone "$domain" {
        type slave;
        file "$zoneFolder/db.$domain";
        allow-query { any; };
        masters { $ip; };
};

LJPC;
        }
        if ($dbFileExists) {
            return <<<LJPC
zone "$domain" {
        type master;
        file "$zoneFolder/db.$domain";
        allow-update { none; };
        allow-query { any; };
};

LJPC;
        }

        return '';
    }

    /**
     * If the zone has been seen in the past 5 minutes
     * @return bool
     * @throws \Exception
     */
    public function isAvailableOnServer(): bool {
        return ! ((new DateTime($this->last_seen))->diff(new DateTime)->m > 5);
    }
}
