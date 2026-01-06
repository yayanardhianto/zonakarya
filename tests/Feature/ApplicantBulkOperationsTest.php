<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Application;
use App\Models\Applicant;
use App\Models\TestSession;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;

class ApplicantBulkOperationsTest extends TestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        // Create admin user for authentication
        $admin = User::factory()->create([
            'role' => 'admin'
        ]);

        Auth::login($admin);
    }

    /** @test */
    public function it_can_bulk_delete_applicants()
    {
        // Create test applicants directly without factory
        $user1 = User::create([
            'name' => 'Test User 1',
            'email' => 'test1@example.com',
            'password' => bcrypt('password'),
            'role' => 'user'
        ]);

        $user2 = User::create([
            'name' => 'Test User 2',
            'email' => 'test2@example.com',
            'password' => bcrypt('password'),
            'role' => 'user'
        ]);

        $user3 = User::create([
            'name' => 'Test User 3',
            'email' => 'test3@example.com',
            'password' => bcrypt('password'),
            'role' => 'user'
        ]);

        $applicant1 = Applicant::create([
            'user_id' => $user1->id,
            'name' => 'Test Applicant 1',
            'email' => 'test1@example.com',
            'phone' => '1234567890',
            'whatsapp' => '1234567890',
            'status' => 'pending'
        ]);

        $applicant2 = Applicant::create([
            'user_id' => $user2->id,
            'name' => 'Test Applicant 2',
            'email' => 'test2@example.com',
            'phone' => '1234567891',
            'whatsapp' => '1234567891',
            'status' => 'pending'
        ]);

        $applicant3 = Applicant::create([
            'user_id' => $user3->id,
            'name' => 'Test Applicant 3',
            'email' => 'test3@example.com',
            'phone' => '1234567892',
            'whatsapp' => '1234567892',
            'status' => 'pending'
        ]);

        $applicantIds = [$applicant1->id, $applicant2->id];

        // Make bulk delete request
        $response = $this->deleteJson('/admin/applicants/bulk-delete', [
            'applicant_ids' => $applicantIds
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Selected applicants deleted successfully.'
                ]);

        // Verify applicants were deleted
        $this->assertDatabaseMissing('applicants', ['id' => $applicant1->id]);
        $this->assertDatabaseMissing('applicants', ['id' => $applicant2->id]);
        $this->assertDatabaseHas('applicants', ['id' => $applicant3->id]);
    }

    /** @test */
    public function it_can_bulk_reject_applicants()
    {
        // Create test applicants directly
        $user1 = User::create([
            'name' => 'Test User 4',
            'email' => 'test4@example.com',
            'password' => bcrypt('password'),
            'role' => 'user'
        ]);

        $user2 = User::create([
            'name' => 'Test User 5',
            'email' => 'test5@example.com',
            'password' => bcrypt('password'),
            'role' => 'user'
        ]);

        $applicant1 = Applicant::create([
            'user_id' => $user1->id,
            'name' => 'Test Applicant 4',
            'email' => 'test4@example.com',
            'phone' => '1234567893',
            'whatsapp' => '1234567893',
            'status' => 'pending'
        ]);

        $applicant2 = Applicant::create([
            'user_id' => $user2->id,
            'name' => 'Test Applicant 5',
            'email' => 'test5@example.com',
            'phone' => '1234567894',
            'whatsapp' => '1234567894',
            'status' => 'pending'
        ]);

        $applicantIds = [$applicant1->id, $applicant2->id];

        // Make bulk reject request
        $response = $this->postJson('/admin/applicants/bulk-reject', [
            'applicant_ids' => $applicantIds,
            'notes' => 'Bulk rejected for testing'
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Selected applicants rejected successfully.'
                ]);

        // Verify applicants were rejected
        $this->assertDatabaseHas('applicants', [
            'id' => $applicant1->id,
            'status' => 'rejected'
        ]);
        $this->assertDatabaseHas('applicants', [
            'id' => $applicant2->id,
            'status' => 'rejected'
        ]);
    }

    /** @test */
    public function it_validates_bulk_delete_request()
    {
        // Make bulk delete request without applicant_ids
        $response = $this->deleteJson('/admin/applicants/bulk-delete', []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['applicant_ids']);
    }

    /** @test */
    public function it_validates_bulk_reject_request()
    {
        // Make bulk reject request without applicant_ids
        $response = $this->postJson('/admin/applicants/bulk-reject', []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['applicant_ids']);
    }
}