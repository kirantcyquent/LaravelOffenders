<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class DeProfile extends Model
{
    //
    protected $fillable = ['url', 'county_id', 'hash'];
    public $timestamps = false;
}
