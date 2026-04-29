<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Equipment extends Model
{
    use HasFactory;

    protected $table = 'equipments';

    // Use protected $primaryKey if your migration used 'equipment_id'
    protected $primaryKey = 'equipment_id';
    public $incrementing = true;        
    protected $keyType = 'int';         

   protected $fillable = ['name', 'category', 'status', 'last_maintenance', 'next_maintenance'];
}
