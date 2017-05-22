<?php

class DatabaseSeeder extends Seeder
{
    protected function tablesToTruncate(): array
    {
        return [
            'users'
        ];
    }

    protected function seeders(): array
    {
        return [
            AdminUserSeeder::class,
        ];
    }

}
