<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    protected $fillable = [
        'performed_by',
        'role',
        'action',
        'target',
        'icon',
        'color',
    ];

    public static function record(string $action, string $target = null, string $icon = 'fa-circle-info', string $color = 'blue')
    {
        $user = Auth::guard('admin')->user();

        self::create([
            'performed_by' => $user?->familyname ?? 'System',
            'role'         => $user?->role ?? 'system',
            'action'       => $action,
            'target'       => $target,
            'icon'         => $icon,
            'color'        => $color,
        ]);
    }
}
