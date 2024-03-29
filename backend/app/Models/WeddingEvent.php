<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WeddingEvent extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'wedding_events';
    protected $fillable = [
        'name',
        'date',
        'start_time',
        'end_time',
        'place',
        'wedding_id',
    ];
    
    protected static function newFactory()
    {
        return \Database\Factories\WeddingEventFactory::new();
    }

    /**
    *
    *  RELATION
    *
    * ---------------------------------------------------------------------
    */

    // Wedding
    public function wedding()
    {
        return $this->belongsTo('App\Models\Wedding');
    }

    /**
    *
    *  METHOD
    *
    * ---------------------------------------------------------------------
    */
}
