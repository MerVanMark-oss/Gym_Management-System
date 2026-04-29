<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    // 1. Tell Laravel staff_id is the primary key
    // Tell Laravel the Primary Key is 'staff_id', not 'id'
    protected $primaryKey = 'staff_id';

    // Since this is $table->id(), it IS an incrementing integer
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'name', 
        'email', 
        'contact', 
        'role', 
        'status', 
        'shift', 
        'hire_date'
    ];
}