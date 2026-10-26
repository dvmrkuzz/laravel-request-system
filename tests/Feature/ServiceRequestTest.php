<?php

namespace Tests\Feature;

use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_shows_request_counts_for_the_signed_in_student(): void
    {
        $student = User::factory()->create();

        $student->serviceRequests()->create([
            'requester_name' => $student->name,
            'requester_email' => $student->email,
            'item_name' => 'HDMI cable',
            'quantity' => 2,
            'purpose' => 'Classroom display',
            'status' => 'pending',
        ]);

        $this->actingAs($student)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Pending')
            ->assertSee('1');
    }

    public function test_guests_cannot_access_requests(): void
    {
        $this->get(route('requests.index'))->assertRedirect(route('login'));
        $this->get(route('requests.create'))->assertRedirect(route('login'));
    }

    public function test_student_can_submit_a_request(): void
    {
        $student = User::factory()->create(['name' => 'Andrea Salcedo']);

        $this->actingAs($student)
            ->post(route('requests.store'), [
                'item_name' => 'HDMI cable',
                'quantity' => 2,
                'purpose' => 'Classroom display',
            ])
            ->assertRedirect(route('requests.index'));

        $this->assertDatabaseHas('requests', [
            'user_id' => $student->id,
            'requester_name' => $student->name,
            'requester_email' => $student->email,
            'item_name' => 'HDMI cable',
            'quantity' => 2,
            'purpose' => 'Classroom display',
            'status' => 'pending',
        ]);
    }

    public function test_student_sees_only_own_requests(): void
    {
        $studentA = User::factory()->create(['name' => 'Andrea Salcedo']);
        $studentB = User::factory()->create(['name' => 'Rafael Uy']);

        $own = $studentA->serviceRequests()->create([
            'requester_name' => $studentA->name,
            'requester_email' => $studentA->email,
            'item_name' => 'HDMI cable',
            'quantity' => 2,
            'purpose' => 'Classroom display',
            'status' => 'pending',
        ]);

        $studentB->serviceRequests()->create([
            'requester_name' => $studentB->name,
            'requester_email' => $studentB->email,
            'item_name' => 'Whiteboard',
            'quantity' => 1,
            'purpose' => 'Lecture hall',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($studentA)->get(route('requests.index'));

        $response->assertOk();
        $response->assertSee('HDMI cable');
        $response->assertDontSee('Whiteboard');
        $this->assertTrue($own->is($studentA->serviceRequests()->first()));
    }

    public function test_admin_sees_all_requests(): void
    {
        $admin = User::factory()->administrator()->create();
        $studentA = User::factory()->create();
        $studentB = User::factory()->create();

        $studentA->serviceRequests()->create([
            'requester_name' => $studentA->name,
            'requester_email' => $studentA->email,
            'item_name' => 'HDMI cable',
            'quantity' => 2,
            'purpose' => 'Classroom display',
            'status' => 'pending',
        ]);

        $studentB->serviceRequests()->create([
            'requester_name' => $studentB->name,
            'requester_email' => $studentB->email,
            'item_name' => 'Whiteboard',
            'quantity' => 1,
            'purpose' => 'Lecture hall',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->get(route('requests.index'));

        $response->assertOk();
        $response->assertSee('HDMI cable');
        $response->assertSee('Whiteboard');
        $response->assertDontSee('New request');
    }

    public function test_student_cannot_view_another_students_request(): void
    {
        $studentA = User::factory()->create();
        $studentB = User::factory()->create();

        $requestA = $studentA->serviceRequests()->create([
            'requester_name' => $studentA->name,
            'requester_email' => $studentA->email,
            'item_name' => 'HDMI cable',
            'quantity' => 2,
            'purpose' => 'Classroom display',
            'status' => 'pending',
        ]);

        $this->actingAs($studentB)
            ->get(route('requests.show', $requestA))
            ->assertForbidden();
    }

    public function test_admin_can_update_request_status(): void
    {
        $admin = User::factory()->administrator()->create();
        $student = User::factory()->create();

        $serviceRequest = $student->serviceRequests()->create([
            'requester_name' => $student->name,
            'requester_email' => $student->email,
            'item_name' => 'HDMI cable',
            'quantity' => 2,
            'purpose' => 'Classroom display',
            'status' => 'pending',
        ]);

        $this->actingAs($admin)
            ->patch(route('requests.status', $serviceRequest), ['status' => 'approved'])
            ->assertRedirect(route('requests.show', $serviceRequest));

        $this->assertSame('approved', $serviceRequest->fresh()->status);
    }

    public function test_student_cannot_update_request_status(): void
    {
        $student = User::factory()->create();

        $serviceRequest = $student->serviceRequests()->create([
            'requester_name' => $student->name,
            'requester_email' => $student->email,
            'item_name' => 'HDMI cable',
            'quantity' => 2,
            'purpose' => 'Classroom display',
            'status' => 'pending',
        ]);

        $this->actingAs($student)
            ->patch(route('requests.status', $serviceRequest), ['status' => 'approved'])
            ->assertForbidden();

        $this->assertSame('pending', $serviceRequest->fresh()->status);
    }

    public function test_administrator_cannot_submit_requests(): void
    {
        $admin = User::factory()->administrator()->create();

        $this->actingAs($admin)
            ->get(route('requests.create'))
            ->assertForbidden();

        $this->actingAs($admin)
            ->post(route('requests.store'), [
                'item_name' => 'HDMI cable',
                'quantity' => 2,
                'purpose' => 'Classroom display',
            ])
            ->assertForbidden();
    }

    public function test_request_validation_errors_are_shown(): void
    {
        $student = User::factory()->create();

        $this->actingAs($student)
            ->from(route('requests.create'))
            ->post(route('requests.store'), [
                'item_name' => '',
                'quantity' => 0,
                'purpose' => '',
            ])
            ->assertRedirect(route('requests.create'))
            ->assertSessionHasErrors(['item_name', 'quantity', 'purpose']);
    }

    public function test_registration_cannot_create_an_administrator(): void
    {
        $this->post('/register', [
            'name' => 'Intruder',
            'email' => 'intruder@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'is_admin' => true,
        ])->assertRedirect(route('dashboard', absolute: false));

        $this->assertDatabaseHas('users', [
            'email' => 'intruder@example.com',
            'is_admin' => 0,
        ]);
    }
}
