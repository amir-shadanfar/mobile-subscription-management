<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OsCredential extends Model
{
    protected $fillable = [
        'application_id',
        'os',
        'username',
        'password',
    ];

    public function app()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }
}
