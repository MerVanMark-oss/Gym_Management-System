<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipType extends Model
{
    use HasFactory;

    // 1. Explicitly set the table name to match your migration
    protected $table = 'membership_types';

    // 2. Allow these columns to be filled
    protected $fillable = [
        'name',
        'price',
        'duration_days'
    ];

    /**
     * Relationship: One Membership Type has many Members
     * (e.g., The 'Monthly' plan is used by many gym-goers)
     */
    public function members()
    {
        return $this->hasMany(Member::class, 'membership_type_id');
    }
}
