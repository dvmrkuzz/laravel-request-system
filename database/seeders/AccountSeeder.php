<?php

namespace Database\Seeders;

use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        $studentA = User::updateOrCreate(
            ['email' => 'student.a@example.com'],
            [
                'name' => 'Andrea Salcedo',
                'password' => Hash::make('StudentA#2026'),
                'is_admin' => false,
            ]
        );

        $studentB = User::updateOrCreate(
            ['email' => 'student.b@example.com'],
            [
                'name' => 'Rafael Uy',
                'password' => Hash::make('StudentB#2026'),
                'is_admin' => false,
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Records Office Administrator',
                'password' => Hash::make('Admin#2026'),
                'is_admin' => true,
            ]
        );

        ServiceRequest::whereNull('user_id')
            ->where('requester_email', 'andrea.salcedo@example.com')
            ->update(['user_id' => $studentA->id]);

        ServiceRequest::whereNull('user_id')
            ->where('requester_email', 'rafael.uy@example.com')
            ->update(['user_id' => $studentB->id]);

        ServiceRequest::whereNull('user_id')
            ->update(['user_id' => $studentA->id]);
    }
}