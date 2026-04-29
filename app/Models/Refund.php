<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Refund extends Model
{
    // These MUST match the columns in your migrations exactly
    protected $fillable = [
        'member_id',
        'membership_type_id',
        'reason',
        'status',
        'disbursement_status',
        'disbursement_date',
         // Added this since your new migration includes it
    ];

    /**
     * Relationship to the Member
     * references 'member_id' on 'members' table
     */
    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'member_id');
    }

    /**
     * Relationship to the Membership Type
     */
    public function membershipType()
    {
        return $this->belongsTo(MembershipType::class, 'membership_type_id');
    }

}