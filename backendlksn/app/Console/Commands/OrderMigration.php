<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class OrderMigration extends Command
{
    protected $signature = 'migrate:order';
    protected $description = 'Execute the migrations in the order specified in the file app/Console/Comands/MigrateInOrder.php \n Drop all the table in db before execute the command.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $migrations = [ 
                        '2022_09_20_123139_create_divisions_table.php',
                        '2014_10_12_000000_create_users_table.php',
                        '2014_10_12_100000_create_password_resets_table.php',
                        '2019_08_19_000000_create_failed_jobs_table.php',
                        '2019_12_14_000001_create_personal_access_tokens_table.php',
                        '2022_09_20_123148_create_polls_table.php',
                        '2022_09_20_123127_create_choices_table.php',
                        '2022_09_20_123154_create_votes_table.php'
        ];

        $this->call('migrate:reset');

        foreach ($migrations as $migration) {
            $basePath = 'database/migrations/';
            $path = $basePath.$migration;
            $this->call('migrate', [
                '--path' => $path ,            
            ]);
        }
    }
} 
