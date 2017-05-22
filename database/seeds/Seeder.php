<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder as BaseSeeder;

abstract class Seeder extends BaseSeeder
{
    abstract protected function tablesToTruncate(): array;

    abstract protected function seeders(): array;

    public function run()
    {
        Model::unguard();

        $this->truncateTables();
        $this->seeding();

        Model::reguard();
    }

    protected function truncateTables(): void
    {
        foreach ($this->tablesToTruncate() as $table) {
            app('db')->table($table)->truncate();
        }
    }

    protected function seeding()
    {
        foreach ($this->seeders() as $seeder) {
            $this->call($seeder);
        }
    }
}