<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class HypothesisTest extends TestCase
{
    use RefreshDatabase;

    // H1 (D-01): Company cannot update competitor's app status
    public function test_h1_company_cannot_update_competitors_application_status()
    {
        $companyA = User::factory()->create(['user_type' => 2]);
        $companyB = User::factory()->create(['user_type' => 2]);
        $candidate = User::factory()->create(['user_type' => 1]);

        $jobId = DB::table('job_circulars')->insertGetId([
            'user_id' => $companyA->id,
            'title' => 'Test Job',
            'job_type' => 'Full Time',
            'openings' => 1,
            'category' => 'IT',
            'skills_needed' => 'PHP',
            'description' => 'Desc',
            'job_details' => 'Details',
            'company_name' => 'Company A',
            'educations' => 'BSc CSE', 
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $appId = DB::table('applications')->insertGetId([
            'user_id' => $candidate->id,
            'job_circular_id' => $jobId,
            'status' => 'Pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($companyB)->post("/application/{$appId}/status", [
            'status' => 'Rejected'
        ]);

        $response->assertStatus(403); 
    }

    // H2 (D-02): Admin registration blocked
    public function test_h2_user_cannot_register_with_admin_user_type()
    {
        $response = $this->post('/register', [
            'name' => 'Hacker',
            'email' => 'hacker@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'user_type' => 'admin',
        ]);

        $response->assertSessionHasErrors('user_type');
    }

    // H4 (D-03): Missing 404 response
    public function test_h4_applying_to_non_existent_job_handles_gracefully()
    {
        // FIX: Use the string 'candidate' to pass the controller's Auth check
        $candidate = User::factory()->create(['user_type' => 'candidate']);
        
        $response = $this->actingAs($candidate)->post('/jobs/apply/9999');
        
        $response->assertStatus(404);
    }
}