<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalesRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'customer_name', 
        'product', 
        'quantity', 
        'unit_price', 
        'total_price', 
        'status', 
        'date'
    ];

    protected $casts = [
        'date' => 'date',
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CANCELLED => 'Cancelled',
        ];
    }

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
