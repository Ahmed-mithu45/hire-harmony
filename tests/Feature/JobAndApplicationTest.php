<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class JobAndApplicationTest extends TestCase
{
    use RefreshDatabase;

    // H1: Does POST /application/{id}/status lack ownership checks?
    public function test_h1_company_cannot_update_competitors_application_status()
    {
        $companyA = User::factory()->create(['user_type' => 2]);
        $companyB = User::factory()->create(['user_type' => 2]);
        $candidate = User::factory()->create(['user_type' => 1]);

        // Direct DB insert with the missing 'educations' column
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
            'educations' => 'BSc CSE', // ADDED MISSING COLUMN
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

        // Company B tries to change the status of Company A's job application
        $response = $this->actingAs($companyB)->post("/application/{$appId}/status", [
            'status' => 'Rejected'
        ]);

        // Expect 403 Forbidden. If 302/200, H1 is proven as a Defect.
        $response->assertStatus(403); 
    }
}