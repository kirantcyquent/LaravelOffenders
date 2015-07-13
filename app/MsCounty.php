<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class MsCounty extends Model
{
    //
    protected $fillable = ['name', 'county_id'];
    public $timestamps = false;
}
