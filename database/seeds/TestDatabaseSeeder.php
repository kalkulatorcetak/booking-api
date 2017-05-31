<?php

class TestDatabaseSeeder extends Seeder
{
    protected function tablesToTruncate(): array
    {
        return [
            'users',
        ];
    }

    protected function seeders(): array
    {
        return [
            AdminUserSeeder::class,
            UsersTableSeeder::class,
            CassaTableSeeder::class,
        ];
    }
}
