<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowLogbook extends Model
{
    protected $table = 'borrow_logbook';
    protected $fillable = [
        'borrow_date',
        'borrower_name',
        'department',
        'equipment',
        'item_asset_code',
        'department_head_name',
        'purpose_borrowing',
        'handled_by',
        'date_returned',
        'remarks',
    ];

}
