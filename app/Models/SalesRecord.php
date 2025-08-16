<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalesRecord extends Model
{
    use HasFactory;

    protected $fillable = ['employee_id', 'date', 'client_name', 'product', 'amount', 'quota'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    // Calculate total price before saving
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($sale) {
            if ($sale->quantity && $sale->unit_price) {
                $sale->total_price = $sale->quantity * $sale->unit_price;
            }
        });
        
        static::updating(function ($sale) {
            if ($sale->quantity && $sale->unit_price) {
                $sale->total_price = $sale->quantity * $sale->unit_price;
            }
        });
    }
}
