<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningActivity extends Model
{
    use HasFactory;
    protected $fillable = ['learning_method'];

    public function activityMonths()
    {
        return $this->hasMany(ActivityMonth::class);
    }
}
