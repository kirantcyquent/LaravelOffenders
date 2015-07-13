<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class GaOffense extends Model
{
    //

    protected $fillable = [ 'of_offendersid', 'of_Offense', 'of_date', 'hash' ];
    public $timestamps = false;
}
