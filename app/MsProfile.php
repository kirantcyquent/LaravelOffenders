<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class MsProfile extends Model
{
    //
    protected $fillable = ['url', 'county_id', 'hash'];
    public $timestamps = false;
}
