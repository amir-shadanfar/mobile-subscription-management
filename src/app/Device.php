<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = [
        'uid',
        'language',
        'os',
        'token',
    ];

    public function applications()
    {
        return $this->belongsToMany(Application::class, 'applications_devices', 'device_id', 'application_id')
            ->withTimestamps()
            ->withPivot(['subscription_status','subscription_expire_date']);
    }
}
