<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupplyTransactionItem extends Model
{
    use HasFactory;

    protected $table = 'supply_transaction_items';

    protected $fillable = [
        'supply_transaction_id',
        'supply_id',
        'quantity',
    ];

    // Link back to the transaction
    public function transaction()
    {
        return $this->belongsTo(SupplyTransaction::class, 'supply_transaction_id');
    }

    // Link to the actual supply
    public function supply()
    {
        return $this->belongsTo(OfficeSupplies::class, 'supply_id');
    }

    protected static function booted()
    {
        static::created(function ($item) {
            $supply = $item->supply;
            $type   = $item->transaction->type;

            if ($type === 'IN') {
                $supply->increment('stock', $item->quantity);
            }

            if ($type === 'OUT') {
                $supply->decrement('stock', $item->quantity);
            }

            if ($type === 'ADJUSTMENT') {
                // adjustment can be + or -
                $supply->increment('stock', $item->quantity);
            }
        });
    }
}
