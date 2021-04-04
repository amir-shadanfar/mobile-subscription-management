<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = [
        'title',
        'code'
    ];

    public function devices()
    {
        return $this->belongsToMany(Device::class, 'applications_devices', 'application_id', 'device_id');
    }

    public function osCredentials()
    {
        return $this->hasMany(OsCredential::class, 'application_id');
    }

    public function eventEndpoints()
    {
        return $this->hasMany(EventEndPoint::class, 'application_id');
    }
}
