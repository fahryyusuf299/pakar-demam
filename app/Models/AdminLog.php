<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminLog extends Model
{
    protected $table = 'admin_logs';

    protected $fillable = [
        'admin_id',
        'action',
        'description',
        'ip_address',
        'user_agent',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public static function record($action, $description)
    {
        $admin = auth('admin')->user();
        return static::create([
            'admin_id' => $admin ? $admin->id : null,
            'action' => $action,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
        ]);
    }
}
