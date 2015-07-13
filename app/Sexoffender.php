<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Sexoffender extends Model
{
    //
    public $timestamps = false;
    public $dates = ["started_at", "completed_at"];
}
