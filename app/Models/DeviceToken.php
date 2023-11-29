<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class DeviceToken extends Model{
    
    public function scopeForWeb($query){
        return $query->where('is_app', 0);
    }
    public function scopeForApp($query){
        return $query->where('is_app', 1);
    }
    public function user($query){
        return $query->belongsTo(User::class);
    }
    
   
}