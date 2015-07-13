<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSexoffendersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('sexoffenders')) {
            Schema::create('sexoffenders', function (Blueprint $table) {
                $table->increments('id');
                $table->string('state_name')->index();
                $table->string('state_code')->index();
                $table->string('state_url');
                $table->string('status');
                $table->integer('records_crawled')->unsigned()->default(0);
                $table->integer('records_expected')->unsigned()->default(0);
                $table->enum('crawl_state',['running','stopped','paused','completed','incomplete'])->default('incomplete')->index();
                $table->enum('paused',['0','1'])->default('0')->comment= '0-normal, 1-paused';
                $table->timestamp('expected_time')->nullable();
                $table->timestamp('started_at')->nullable();
                $table->timestamp('completed_at')->nullable();

            });
        }
        if (!Schema::hasTable('sexoffenders_stats')) {
            Schema::create('sexoffenders_stats', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('sexoffender_id')->unsigned();
                $table->foreign('sexoffender_id')->references('id')->on('sexoffenders');
                $table->integer('records_crawled')->unsigned()->default(0);
                $table->time('crawl_time');
                $table->time('record_time');
                $table->timestamp('started_at')->nullable();
                $table->timestamp('completed_at')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sexoffenders_stats', function(Blueprint $table)
        {
            $table->dropForeign('sexoffenders_stats_sexoffender_id_foreign');
        });
        Schema::drop('sexoffenders');
    }
}
