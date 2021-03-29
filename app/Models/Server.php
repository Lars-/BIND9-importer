<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Server
 *
 * @property string ip
 * @property string db_hostname
 * @property string db_username
 * @property string db_database
 * @property string db_table
 * @property string db_domain_name_column
 * @property string db_password
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Server newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Server newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Server query()
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereDbDatabase($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereDbDomainNameColumn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereDbHostname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereDbPassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereDbTable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereDbUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Server extends Model {
    protected $fillable = [
        'ip',
        'db_hostname',
        'db_username',
        'db_database',
        'db_table',
        'db_domain_name_column',
        'db_password',
    ];
}
