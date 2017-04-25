<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    protected $tables = [
        'users'
    ];

    public function run()
    {
        Model::unguard();

        foreach($this->tables as $table){
            DB::table($table)->truncate();
            $this->call(ucfirst($table) . 'TableSeeder');
        }

        Model::reguard();
    }
}
