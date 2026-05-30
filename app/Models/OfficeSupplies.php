<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class OfficeSupplies extends Model
{
    use HasFactory;
    protected $table = 'office_supplies';

    protected $fillable = [
        'name',
        'brand',
        'description',
        'category',
        'stock',
        'unit',
    ]; 

    public function transactionItems()
    {
        return $this->hasMany(SupplyTransactionItem::class);
    }

    // Optional: calculate current stock if you want
    // public function getCurrentStockAttribute()
    // {
    //     return $this->transactionItems()->sum('quantity');
    // }
}
