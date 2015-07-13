<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSexoffendersMississippi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ms_offenders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('so_id');
            $table->string('so_offenderid');
            $table->string('so_name');
            $table->text('so_alias')->nullable();
            $table->string('so_address')->nullable();
            $table->string('so_street')->nullable();
            $table->string('so_city')->nullable();
            $table->string('so_state')->nullable();
            $table->string('zip')->nullable();
            $table->string('so_statesource');
            $table->string('so_addressdate');
            $table->string('so_ethnicity');
            $table->string('so_race')->nullable();
            $table->enum('so_sex',['Male','Female']);
            $table->string('so_height')->nullable();
            $table->string('so_weight')->nullable();
            $table->string('so_eyes')->nullable();
            $table->string('so_hair')->nullable();
            $table->string('so_dob')->nullable();
            $table->string('so_age')->nullable();
            $table->string('so_url')->nullable();
            $table->text('so_vehicles')->nullable();
            $table->string('so_targets')->nullable();
            $table->enum('so_profilegenerated', ['Y', 'N'])->nullable();
            $table->enum('so_duplicate', ['Y', 'N'])->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('got_it_from')->nullable();
        });

        Schema::create('ms_offenses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('of_offendersid');
            $table->string('of_Offense')->nullable();
            $table->string('of_ConvictedDate');
            $table->string('of_Degree');
            $table->string('of_Counts');
            $table->string('of_date')->nullable();
            $table->string('hash',32)->unique();
        });

        Schema::create('ms_profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('url');
            $table->string('county_id',20);
            $table->enum('status',['0','1','2'])->comment = '0-Fresh, 1-Crawled, 2-Offender-not-Found';
            $table->string('hash',32)->unique();
        });

        Schema::create('ms_counties', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('county_id',20);
            $table->enum('status',['0','1','2','3','4'])->comment = '0-fresh, 1-html-saved, 2-server-error, 3-links-extracted, 4-nolinks';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ms_offenders');
        Schema::drop('ms_offenses');
        Schema::drop('ms_profiles');
        Schema::drop('ms_counties');
    }

}









































