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
        User::unguarded(function (): void {
            $studentA = User::updateOrCreate(
                ['email' => 'student.a@example.com'],
                [
                    'name' => 'Andrea Salcedo',
                    'password' => Hash::make('StudentA#2026'),
                    'is_admin' => false,
                    'email_verified_at' => now(),
                ]
            );

            $studentB = User::updateOrCreate(
                ['email' => 'student.b@example.com'],
                [
                    'name' => 'Rafael Uy',
                    'password' => Hash::make('StudentB#2026'),
                    'is_admin' => false,
                    'email_verified_at' => now(),
                ]
            );

            User::updateOrCreate(
                ['email' => 'admin@example.com'],
                [
                    'name' => 'Records Office Administrator',
                    'password' => Hash::make('Admin#2026'),
                    'is_admin' => true,
                    'email_verified_at' => now(),
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

            $studentA->serviceRequests()->firstOrCreate(
                ['item_name' => 'HDMI cable'],
                [
                    'requester_name' => $studentA->name,
                    'requester_email' => $studentA->email,
                    'quantity' => 2,
                    'purpose' => 'Classroom display for laboratory sessions.',
                    'status' => 'pending',
                ]
            );

            $studentB->serviceRequests()->firstOrCreate(
                ['item_name' => 'Whiteboard'],
                [
                    'requester_name' => $studentB->name,
                    'requester_email' => $studentB->email,
                    'quantity' => 1,
                    'purpose' => 'Lecture hall replacement board.',
                    'status' => 'pending',
                ]
            );
        });
    }
}
