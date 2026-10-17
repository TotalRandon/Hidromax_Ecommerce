<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $casts = [
        'approval_decided_at' => 'datetime',
        'shipped_date' => 'datetime',
    ];

    public function items() {
        return $this->hasMany(OrderItem::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approval_decided_by');
    }
}
