<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call('SexoffenderSeeder');
        $this->command->info('Sex Offender States table seeded!');
        $this->call('KansasCountiesSeeder');
        $this->command->info('Kansas Counties table seeded!');
        $this->call('MississippiCountiesSeeder');
        $this->command->info('Mississippi Counties table seeded!');
        $this->call('GeorgiaCountiesSeeder');
	}
}
