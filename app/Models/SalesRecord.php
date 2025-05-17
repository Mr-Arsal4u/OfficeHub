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
}
