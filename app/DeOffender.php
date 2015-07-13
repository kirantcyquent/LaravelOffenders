<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class DeOffender extends Model
{
    //
    protected $fillable = [
        'so_id',
        'so_offenderid',
        'so_name',
        'so_alias',
        'so_address',
        'so_street',
        'so_city',
        'so_state',
        'zip',
        'race',
        'so_sex',
        'so_height',
        'so_weight',
        'so_eyes',
        'so_hair',
        'so_dob',
        'so_age',
        'so_url',
        'so_targets',
        'latitude',
        'longitude',
        'got_it_from'
    ];
    public $timestamps = false;
}
