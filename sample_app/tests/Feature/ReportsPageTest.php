<?php

namespace Tests\Feature;

use App\Models\Report;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ReportsPageTest extends TestCase
{
    public function test_reports_page_does_not_render_dummy_issue_data(): void
    {
        $response = $this->get('/reports');

        $response->assertOk();
        $response->assertDontSee('Large pothole near Maple Ave');
        $response->assertDontSee('Broken streetlight');
    }

    public function test_report_can_be_created_from_the_form(): void
    {
        Storage::fake('public');

        $response = $this->post('/reports', [
            'title' => 'Flooded sidewalk',
            'description' => 'Water is pooling near the bus stop after heavy rain.',
            'type' => 'Flooding',
            'location' => 'Oak Street & 9th Ave',
            'priority' => 'high',
            'image' => UploadedFile::fake()->create('flooded-sidewalk.jpg', 100, 'image/jpeg'),
        ]);

        $response->assertRedirect('/reports');
        $this->assertDatabaseHas('reports', [
            'title' => 'Flooded sidewalk',
            'location' => 'Oak Street & 9th Ave',
            'type' => 'Flooding',
        ]);

        $report = \App\Models\Report::query()->where('title', 'Flooded sidewalk')->first();
        $this->assertNotNull($report);
        $this->assertNotNull($report->image_path);
        Storage::disk('public')->assertExists($report->image_path);
    }

    public function test_report_exposes_a_public_image_url_from_the_database_path(): void
    {
        $report = Report::make([
            'title' => 'Broken gate',
            'description' => 'The front gate is damaged.',
            'type' => 'Infrastructure',
            'location' => 'Maple Ave',
            'priority' => 'medium',
            'status' => 'open',
            'image_path' => 'reports/broken-gate.jpg',
        ]);

        $this->assertSame(
            Storage::disk('public')->url('reports/broken-gate.jpg'),
            $report->image_url
        );
    }

    public function test_user_can_update_their_profile_name_and_avatar(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'profile@example.com',
        ]);

        $this->actingAs($user);

        $response = $this->post('/account', [
            'name' => 'Updated Name',
            'avatar' => UploadedFile::fake()->create('avatar.jpg', 100, 'image/jpeg'),
        ]);

        $response->assertRedirect('/account');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
        ]);

        $user->refresh();
        $this->assertNotNull($user->avatar_path);
        Storage::disk('public')->assertExists($user->avatar_path);
    }

    public function test_admin_can_manage_reports_from_private_dashboard(): void
    {
        $admin = User::factory()->create([
            'name' => 'Site Admin',
            'email' => 'admin@communityconnect.test',
        ]);

        $resident = User::factory()->create([
            'name' => 'Normal Resident',
            'email' => 'resident@example.com',
        ]);

        $report = Report::create([
            'title' => 'Blocked drain',
            'description' => 'Water is pooling at the corner.',
            'type' => 'Flooding',
            'location' => 'Central Ave',
            'priority' => 'high',
            'status' => 'open',
            'meta' => ['tone' => 'danger', 'residents' => 1],
        ]);

        $this->actingAs($resident)->get('/admin')->assertForbidden();

        $this->actingAs($admin)
            ->get('/admin')
            ->assertOk();

        $this->actingAs($admin)
            ->post('/admin/reports/' . $report->id . '/resolve')
            ->assertRedirect('/admin');

        $report->refresh();
        $this->assertSame('resolved', $report->status);

        $this->actingAs($admin)
            ->post('/admin/reports/' . $report->id . '/comments', [
                'comment' => 'Crews are clearing the drain now.',
            ])
            ->assertRedirect('/admin');

        $this->assertDatabaseHas('report_comments', [
            'report_id' => $report->id,
            'body' => 'Crews are clearing the drain now.',
        ]);

        $this->actingAs($admin)
            ->post('/admin/reports/' . $report->id . '/delete')
            ->assertRedirect('/admin');

        $this->assertDatabaseMissing('reports', ['id' => $report->id]);
    }

    public function test_admin_comments_are_visible_on_the_public_reports_page(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@communityconnect.test',
        ]);

        $report = Report::create([
            'title' => 'Roadside debris',
            'description' => 'There is debris near the bus stop.',
            'type' => 'Trash',
            'location' => 'Shoreline Rd',
            'priority' => 'medium',
            'status' => 'open',
            'meta' => ['tone' => 'warning', 'residents' => 1],
        ]);

        $report->comments()->create([
            'user_id' => $admin->id,
            'body' => 'Maintenance team is scheduled for pickup tomorrow.',
        ]);

        $response = $this->get('/reports');

        $response->assertOk();
        $response->assertSee('Maintenance team is scheduled for pickup tomorrow.');
    }

    public function test_user_can_register_and_be_saved_to_the_database(): void
    {
        $response = $this->post('/register', [
            'name' => 'Maya Patel',
            'email' => 'maya@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertRedirect('/login');
        $this->assertDatabaseHas('users', [
            'email' => 'maya@example.com',
            'name' => 'Maya Patel',
        ]);
    }

    public function test_report_can_store_map_coordinates_and_support_count(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post('/reports', [
            'title' => 'Broken drain near the market',
            'description' => 'Water is pooling in front of the market entrance.',
            'type' => 'Infrastructure',
            'location' => 'Mactan Circumferential Road, Lapu-Lapu City',
            'latitude' => '10.3103',
            'longitude' => '123.9499',
            'priority' => 'medium',
        ]);

        $response->assertRedirect('/reports');
        $this->assertDatabaseHas('reports', [
            'title' => 'Broken drain near the market',
            'latitude' => '10.3103',
            'longitude' => '123.9499',
            'support_count' => 0,
        ]);

        $report = Report::query()->where('title', 'Broken drain near the market')->first();
        $this->assertNotNull($report);

        $supportResponse = $this->post('/reports/' . $report->id . '/support');
        $supportResponse->assertRedirect('/reports');

        $report->refresh();
        $this->assertSame(1, (int) $report->support_count);
    }

    public function test_user_can_log_in_with_their_account(): void
    {
        $user = User::factory()->create([
            'name' => 'Maya Patel',
            'email' => 'maya@example.com',
            'password' => bcrypt('secret123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'maya@example.com',
            'password' => 'secret123',
        ]);

        $response->assertRedirect('/reports');
        $this->assertAuthenticatedAs($user);
    }
}
