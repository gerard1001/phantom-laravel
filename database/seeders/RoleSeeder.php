<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $roles = [
            [
                'id' => Uuid::uuid4()->toString(),
                'name' => 'admin'
            ],
            [
                'id' => Uuid::uuid4()->toString(),
                'name' => 'operator'
            ],
            [
                'id' => Uuid::uuid4()->toString(),
                'name' => 'driver'
            ],
        ];

        foreach ($roles as $role) {
            Role::withoutEvents(fn () => Role::create($role));
        };
    }
}
