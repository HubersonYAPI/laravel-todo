<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Todo extends Model
{
    use Notifiable;
    
    protected $fillable = [
        'name', 'description', 'done',
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'creator_id');
    }

    public function todoAffectedTo()
    {
        return $this->belongsTo('App\User', 'affectedTo_id');
    }

    public function todoAffectedBy()
    {
        return $this->belongsTo('App\User', 'affectedBy_id');
    }


}
