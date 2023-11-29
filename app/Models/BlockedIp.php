<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class BlockedIp extends Model
{
    use Searchable;

    protected $fillable = [
        'ip_address'
    ];
}
