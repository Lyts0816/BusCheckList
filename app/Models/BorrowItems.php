<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BorrowItems extends Model
{
    protected $table = 'borrow_items';

    protected $fillable = [
        'borrow_id',
        'item_name',
        'item_asset_code',
        'quantity',
        'return_date',
    ];

    public function borrowLog(): BelongsTo
    {
        return $this->belongsTo(BorrowLogs::class, 'borrow_id');
    }
}
