<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase; 

    public function test_admin_can_delete_a_user_account(): void
    {
        $admin = User::create([
            'name' => 'System Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('1234admin'),
            'user_type' => 'admin',
            'role' => 'admin', // This is what your middleware checks
            'unique_id' => 'ADM-2026-0001',
        ]);

        // 2. Create a fake Company user that we want to delete
        $fakeCompany = User::create([
            'name' => 'Fake Company Ltd',
            'email' => 'fake@example.com',
            'password' => bcrypt('password123'),
            'user_type' => 'company',
            'unique_id' => 'COM-2026-9999',
        ]);

        // Ensure the company exists in the DB before deletion
        $this->assertDatabaseHas('users', [
            'email' => 'fake@example.com'
        ]);

        // 3. Log in as the Admin and send a DELETE request to remove the company
        $response = $this->actingAs($admin)->delete("/admin/user/delete/{$fakeCompany->id}");

        // 4. Assert successful redirect with the success message
        $response->assertStatus(302);
        $response->assertSessionHas('success', 'User and all associated data deleted successfully.');

        // 5. Assert the company no longer exists in the database
        $this->assertDatabaseMissing('users', [
            'email' => 'fake@example.com'
        ]);
    }

    /**
     * NEGATIVE TEST: A regular candidate CANNOT delete a user account.
     */
    public function test_candidate_cannot_delete_a_user_account(): void
    {
        // 1. Create a fake Candidate user
        $candidate = User::create([
            'name' => 'Sneaky Candidate',
            'email' => 'sneaky@example.com',
            'password' => bcrypt('password123'),
            'user_type' => 'candidate',
            'role' => 'user', 
            'unique_id' => 'CAN-2026-8888',
        ]);

        // 2. Create a fake Company user
        $targetCompany = User::create([
            'name' => 'Target Company Ltd',
            'email' => 'target@example.com',
            'password' => bcrypt('password123'),
            'user_type' => 'company',
            'unique_id' => 'COM-2026-7777',
        ]);

        // 3. Log in as the Candidate and try to send a DELETE request
        $response = $this->actingAs($candidate)->delete("/admin/user/delete/{$targetCompany->id}");

        // 4. Assert the system denies access (depending on your middleware, this might be 403 Forbidden or a 302 redirect)
        // Since you just redirect non-admins back to the home/login page, it should be a 302
        $response->assertStatus(302);

        // 5. CRITICAL: Assert the company STILL exists in the database (deletion failed)
        $this->assertDatabaseHas('users', [
            'email' => 'target@example.com'
        ]);
    }
}