<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    protected $tables = [
        'users'
    ];

    public function run()
    {
        Model::unguard();

        foreach($this->tables as $table){
            app('db')->table($table)->truncate();
            $this->call(ucfirst($table) . 'TableSeeder');
        }

        Model::reguard();
    }
}
