<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DispatchSheet extends Model
{
    use HasFactory;

    protected $table = 'dispatch_sheets';

    protected $fillable = [
        'dispatch_date',
    ];

    public function trips()
    {
        return $this->hasMany(DispatchedTrips::class);
    }
    
    protected $casts = [
    'dispatch_date' => 'date',
];
}
