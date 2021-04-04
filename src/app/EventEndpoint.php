<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventEndpoint extends Model
{
    protected $fillable = [
        'application_id',
        'url',
    ];

    public function app()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }
}
