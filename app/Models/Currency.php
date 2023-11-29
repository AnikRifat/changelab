<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\Searchable;
use App\Traits\CommonScope;
use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use Searchable, CommonScope, GlobalStatus;



    public function gatewayCurrency()
    {
        return $this->belongsTo(Gateway::class, 'gateway_id');
    }

    public function userDetailsData()
    {
        return $this->belongsTo(Form::class, 'user_detail_form_id');
    }
    public function transactionProvedData()
    {
        return $this->belongsTo(Form::class, 'trx_proof_form_id');
    }
    public function scopeAvailableForSell($query)
    {
        return $query->where('available_for_sell', Status::YES);
    }
    public function scopeAvailableForBuy($query)
    {
        return $query->where('available_for_buy', Status::YES);
    }
}
