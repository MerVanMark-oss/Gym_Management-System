<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Refund;

class Member extends Model
{
    use HasFactory;

    protected $primaryKey = 'member_id';
    // This tells Laravel which columns are allowed to be filled
    protected $fillable = [
        'first_name', 
        'last_name', 
        'contact_number', 
        'membership_type_id', 
        'join_date', 
        'expiry_date', 
        'status'
    ];

    public function payments()
    {
        // This links the member_id in the payments table to this member
        return $this->hasMany(Payment::class, 'member_id', 'member_id');
    }

    // Relationship to MembershipType
    public function membershipType()
    {
        return $this->belongsTo(MembershipType::class, 'membership_type_id');
    }

    public function refunds()
{
    return $this->hasMany(Refund::class, 'member_id', 'member_id');
}
}