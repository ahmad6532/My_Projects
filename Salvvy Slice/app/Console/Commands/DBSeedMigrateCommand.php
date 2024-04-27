<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DBSeedMigrateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db_dump';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate  and Seed Database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->call('migrate:fresh');
        $this->call('db:seed');
        $this->info('Database Migrated and Seeded Successfully....');
    }
}
