<?php

namespace App\Models;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable 
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = ['name', 'email', 'password'];

   
    // public function financialTransactions() {
    //     return $this->hasMany(FinancialTransaction::class);
    // }

    public function salesRecords() {
        return $this->hasMany(SalesRecord::class);
    }
}
