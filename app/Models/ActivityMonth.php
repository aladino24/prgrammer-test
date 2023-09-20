<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityMonth extends Model
{
    use HasFactory;
    protected $fillable = ['learning_activity_id','month', 'activities', 'start_date', 'end_date'];

    public function learningActivity()
    {
        return $this->belongsTo(LearningActivity::class);
    }
}
