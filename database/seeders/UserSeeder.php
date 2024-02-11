<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = new Role();
        $admin_id = $role->where('name', 'admin')->first()->id;
        $operator_id =  $role->where('name', 'operator')->first()->id;
        $driver_id = $role->where('name', 'driver')->first()->id;

        $users = [
            [
                'id' => Uuid::uuid4()->toString(),
                'role_id' => $admin_id,
                'names' => 'Admin',
                'email' => 'admin1@phantom.com',
                'birth_date' => '1990-01-01',
                'phone_number' => '0780000000',
                'gender' => 'male',
                'password' => Hash::make('pass1234'),
            ],
            [
                'id' => Uuid::uuid4()->toString(),
                'role_id' => $operator_id,
                'names' => 'Operator',
                'email' => 'operator@phantom.com',
                'birth_date' => '1990-01-01',
                'phone_number' => '0780000001',
                'password' => Hash::make('pass1234'),
            ],
            [
                'id' => Uuid::uuid4()->toString(),
                'role_id' => $driver_id,
                'names' => 'Driver',
                'email' => 'driver@phantom.com',
                'birth_date' => '1990-01-01',
                'phone_number' => '0780000002',
                'password' => Hash::make('pass1234'),
            ],
        ];

        foreach ($users as $user) {
            User::withoutEvents(fn () => User::create($user));
        };
    }
}
