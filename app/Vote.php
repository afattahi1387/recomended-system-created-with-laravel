<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $fillable = ['user_id', 'video_id', 'score'];

    public function video() {
        return $this->belongsTo(Video::class);
    }
}
