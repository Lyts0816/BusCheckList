<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class BorrowLogs extends Model
{
    use SoftDeletes;

    protected $table = 'borrow_logs';

    protected $fillable = [
        'borrower_name',
        'department',
        'borrowed_date',
        'purpose_borrowing',
        'department_head_name',
        'handled_by',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(BorrowItems::class, 'borrow_id');
    }
}
