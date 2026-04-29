<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; 
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable 
{
    use Notifiable;

    protected $table = 'admins';
    protected $primaryKey = 'user_id'; // Your actual PK
    
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'username', 
        'password', 
        'contactnum', 
        'email', 
        'familyname', 
        'role', 
        'status', 
        'last_login'
    ];

    /**
     * FIX: This must return the PRIMARY KEY name.
     * Laravel uses this to retrieve the user from the session by ID.
     */
    public function getAuthIdentifierName()
    {
        return 'user_id'; 
    }
    
    protected $hidden = [
        'password',
        'remember_token', 
    ];

    protected $casts = [
        'last_login' => 'datetime',
    ];
}