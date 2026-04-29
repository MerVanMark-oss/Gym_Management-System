<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    // Must be plural to match your database image
    protected $table = 'payments';

    // Must include all columns from your migration
    protected $fillable = [
        'member_id',
        'transaction_id',
        'amount',
        'type',
        'payment_method',
        'status',
    ];

    // Relationship: Links to 'member_id' primary key in members table
    public function member()
    {
       return $this->belongsTo(Member::class, 'member_id', 'member_id');
    }
}
