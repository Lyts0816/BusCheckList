<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{   
    protected $table = 'buses';
    
    protected $fillable = [
        'bus_number',
        'model',
        'status',
        'base_location',
    ];


    public function itemsChecklist()
    {
        return $this->hasMany(ItemsChecklist::class, 'bus_id');
    }

}


