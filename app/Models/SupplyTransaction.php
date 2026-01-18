<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplyTransaction extends Model
{
    protected $table = 'supply_transactions';

    protected $fillable = [
        'type',
        'department',
        'user',
        'remarks',
    ];

    // Relationship to items (repeater in Filament)
    public function items()
    {
        return $this->hasMany(SupplyTransactionItem::class);
    }

    // protected static function booted()
    // {
    //     static::created(function ($transaction) {
    //         foreach ($transaction->items as $item) {
    //             $supply = $item->supply;
                
    //             if ($transaction->type === 'IN') {
    //                 $supply->stock += $item->quantity;
    //             } elseif ($transaction->type === 'OUT') {
    //                 $supply->stock -= $item->quantity;
    //             } elseif ($transaction->type === 'ADJUSTMENT') {
    //                 // For adjustment, you could just add the quantity if positive
    //                 // or subtract if negative
    //                 $supply->stock += $item->quantity; // $item->quantity can be negative
    //             }

    //             $supply->save();
    //         }
    //     });
    // }
}
