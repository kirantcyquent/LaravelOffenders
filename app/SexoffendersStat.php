<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class SexoffendersStat extends Model
{
    //
    public $timestamps = false;
    public $dates = ["started_at", "completed_at"];
}
