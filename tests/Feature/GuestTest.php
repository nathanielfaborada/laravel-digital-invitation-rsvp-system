<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Guest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_be_updated_via_standard_put_request(): void
    {
        $user = User::factory()->create();
        $event = Event::create([
            'user_id' => $user->id,
            'title' => 'Test Event',
            'event_date' => now()->addDays(5)->format('Y-m-d'),
            'event_time' => '18:00:00',
            'venue' => 'Main Hall',
        ]);
        $guest = Guest::create([
            'event_id' => $event->id,
            'name' => 'Original Name',
            'email' => 'old@example.com',
            'phone' => '0000000000',
            'max_companions' => 1,
        ]);

        $response = $this->actingAs($user)->put(route('guests.update', $guest), [
            'name' => 'Updated Guest Name',
            'email' => 'updated@example.com',
            'phone' => '1234567890',
            'max_companions' => 3,
            'status' => 'attending',
        ]);

        $response->assertRedirect(route('events.show', $event));
        $this->assertDatabaseHas('guests', [
            'id' => $guest->id,
            'name' => 'Updated Guest Name',
            'email' => 'updated@example.com',
            'phone' => '1234567890',
            'max_companions' => 3,
        ]);
        $this->assertDatabaseHas('rsvps', [
            'guest_id' => $guest->id,
            'status' => 'attending',
        ]);
    }

    public function test_guest_can_be_updated_via_ajax_json_request(): void
    {
        $user = User::factory()->create();
        $event = Event::create([
            'user_id' => $user->id,
            'title' => 'Test Event',
            'event_date' => now()->addDays(5)->format('Y-m-d'),
            'event_time' => '18:00:00',
            'venue' => 'Main Hall',
        ]);
        $guest = Guest::create([
            'event_id' => $event->id,
            'name' => 'Original Name',
            'email' => 'old@example.com',
            'phone' => '0000000000',
            'max_companions' => 1,
        ]);

        $response = $this->actingAs($user)->json('PUT', route('guests.update', $guest), [
            'name' => 'JSON Guest Name',
            'email' => 'json@example.com',
            'phone' => '0987654321',
            'max_companions' => 2,
            'status' => 'not_attending',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Guest updated successfully!',
        ]);
        $this->assertDatabaseHas('guests', [
            'id' => $guest->id,
            'name' => 'JSON Guest Name',
        ]);
        $this->assertDatabaseHas('rsvps', [
            'guest_id' => $guest->id,
            'status' => 'not_attending',
        ]);
    }

    public function test_guest_edit_route_redirects_to_events_show(): void
    {
        $user = User::factory()->create();
        $event = Event::create([
            'user_id' => $user->id,
            'title' => 'Test Event',
            'event_date' => now()->addDays(5)->format('Y-m-d'),
            'event_time' => '18:00:00',
            'venue' => 'Main Hall',
        ]);
        $guest = Guest::create([
            'event_id' => $event->id,
            'name' => 'Original Name',
        ]);

        $response = $this->actingAs($user)->get(route('guests.edit', $guest));

        $response->assertRedirect(route('events.show', $event));
    }

    public function test_duplicate_guest_cannot_be_added_to_same_event(): void
    {
        $user = User::factory()->create();
        $event = Event::create([
            'user_id' => $user->id,
            'title' => 'Test Event',
            'event_date' => now()->addDays(5)->format('Y-m-d'),
            'event_time' => '18:00:00',
            'venue' => 'Main Hall',
        ]);
        Guest::create([
            'event_id' => $event->id,
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $response = $this->actingAs($user)->post(route('events.guests.store', $event), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $response->assertSessionHas('error', 'Guest already exists in this event list!');
        $this->assertCount(1, Guest::where('event_id', $event->id)->get());
    }

    public function test_guest_deletion_redirects_to_events_show_with_success_toast(): void
    {
        $user = User::factory()->create();
        $event = Event::create([
            'user_id' => $user->id,
            'title' => 'Test Event',
            'event_date' => now()->addDays(5)->format('Y-m-d'),
            'event_time' => '18:00:00',
            'venue' => 'Main Hall',
        ]);
        $guest = Guest::create([
            'event_id' => $event->id,
            'name' => 'To Be Deleted',
            'email' => 'delete@example.com',
        ]);

        $response = $this->actingAs($user)->delete(route('guests.destroy', $guest));

        $response->assertRedirect(route('events.show', $event));
        $response->assertSessionHas('success', 'Guest deleted successfully!');
        $this->assertDatabaseMissing('guests', ['id' => $guest->id]);
    }

    public function test_multiple_guests_can_be_bulk_deleted(): void
    {
        $user = User::factory()->create();
        $event = Event::create([
            'user_id' => $user->id,
            'title' => 'Test Event',
            'event_date' => now()->addDays(5)->format('Y-m-d'),
            'event_time' => '18:00:00',
            'venue' => 'Main Hall',
        ]);
        $guest1 = Guest::create(['event_id' => $event->id, 'name' => 'Guest 1']);
        $guest2 = Guest::create(['event_id' => $event->id, 'name' => 'Guest 2']);
        $guest3 = Guest::create(['event_id' => $event->id, 'name' => 'Guest 3']);

        $response = $this->actingAs($user)->delete(route('guests.bulk-destroy'), [
            'guest_ids' => [$guest1->id, $guest2->id],
        ]);

        $response->assertSessionHas('success', '2 guests deleted successfully!');
        $this->assertDatabaseMissing('guests', ['id' => $guest1->id]);
        $this->assertDatabaseMissing('guests', ['id' => $guest2->id]);
        $this->assertDatabaseHas('guests', ['id' => $guest3->id]);
    }

    public function test_export_fails_with_error_when_guest_count_is_zero(): void
    {
        $user = User::factory()->create();
        $event = Event::create([
            'user_id' => $user->id,
            'title' => 'Empty Event',
            'event_date' => now()->addDays(5)->format('Y-m-d'),
            'event_time' => '18:00:00',
            'venue' => 'Main Hall',
        ]);

        $response = $this->actingAs($user)->get(route('events.guests.export', $event));

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Cannot export an empty guest list. Please add at least 1 guest.');
    }

    public function test_export_succeeds_when_guests_exist(): void
    {
        $user = User::factory()->create();
        $event = Event::create([
            'user_id' => $user->id,
            'title' => 'Populated Event',
            'event_date' => now()->addDays(5)->format('Y-m-d'),
            'event_time' => '18:00:00',
            'venue' => 'Main Hall',
        ]);
        Guest::create([
            'event_id' => $event->id,
            'name' => 'Alice Doe',
            'email' => 'alice@example.com',
        ]);

        $response = $this->actingAs($user)->get(route('events.guests.export', $event));

        $response->assertStatus(200);
        $response->assertHeader('content-disposition', 'attachment; filename="guests-populated-event.csv"');
    }

    public function test_events_show_renders_disabled_export_button_when_no_guests(): void
    {
        $user = User::factory()->create();
        $event = Event::create([
            'user_id' => $user->id,
            'title' => 'Empty Event',
            'event_date' => now()->addDays(5)->format('Y-m-d'),
            'event_time' => '18:00:00',
            'venue' => 'Main Hall',
        ]);

        $response = $this->actingAs($user)->get(route('events.show', $event));

        $response->assertStatus(200);
        $response->assertSee('title="Add at least 1 guest to enable export"', false);
        $response->assertSee('opacity-50 cursor-not-allowed pointer-events-none bg-gray-100 text-gray-400 border-gray-200', false);
        $response->assertSee('disabled', false);
    }

    public function test_events_show_renders_enabled_export_button_when_guests_exist(): void
    {
        $user = User::factory()->create();
        $event = Event::create([
            'user_id' => $user->id,
            'title' => 'Populated Event',
            'event_date' => now()->addDays(5)->format('Y-m-d'),
            'event_time' => '18:00:00',
            'venue' => 'Main Hall',
        ]);
        Guest::create([
            'event_id' => $event->id,
            'name' => 'Alice Doe',
            'email' => 'alice@example.com',
        ]);

        $response = $this->actingAs($user)->get(route('events.show', $event));

        $response->assertStatus(200);
        $response->assertSee('bg-emerald-50 text-emerald-600 hover:bg-emerald-100 border-emerald-200', false);
        $response->assertSee(route('events.guests.export', $event), false);
    }

    public function test_guest_creation_rejects_formula_injection_characters(): void
    {
        $user = User::factory()->create();
        $event = Event::create([
            'user_id' => $user->id,
            'title' => 'Security Test Event',
            'event_date' => now()->addDays(5)->format('Y-m-d'),
            'event_time' => '18:00:00',
            'venue' => 'Main Hall',
        ]);

        $invalidPayloads = [
            ['name' => '=1+1', 'email' => 'valid@example.com', 'field' => 'name'],
            ['name' => '+cmd|', 'email' => 'valid@example.com', 'field' => 'name'],
            ['name' => '-2+3', 'email' => 'valid@example.com', 'field' => 'name'],
            ['name' => '@SUM(1,2)', 'email' => 'valid@example.com', 'field' => 'name'],
            ['name' => 'Valid Name', 'phone' => '+1234567890', 'field' => 'phone'],
            ['name' => 'Valid Name', 'phone_number' => '=SUM(A1)', 'field' => 'phone_number'],
            ['name' => 'Valid Name', 'companion_name' => '@Admin', 'field' => 'companion_name'],
        ];

        foreach ($invalidPayloads as $payload) {
            $field = $payload['field'];
            unset($payload['field']);
            $response = $this->actingAs($user)->post(route('events.guests.store', $event), $payload);
            $response->assertSessionHasErrors([$field => 'Field inputs cannot start with special formula characters like =, +, -, or @.']);
        }
    }

    public function test_guest_update_rejects_formula_injection_characters(): void
    {
        $user = User::factory()->create();
        $event = Event::create([
            'user_id' => $user->id,
            'title' => 'Security Test Event',
            'event_date' => now()->addDays(5)->format('Y-m-d'),
            'event_time' => '18:00:00',
            'venue' => 'Main Hall',
        ]);
        $guest = Guest::create([
            'event_id' => $event->id,
            'name' => 'Original Name',
            'email' => 'original@example.com',
        ]);

        $response = $this->actingAs($user)->put(route('guests.update', $guest), [
            'name' => '=HYPERLINK("http://malicious.site")',
            'email' => 'original@example.com',
        ]);

        $response->assertSessionHasErrors(['name' => 'Field inputs cannot start with special formula characters like =, +, -, or @.']);
    }

    public function test_csv_export_sanitizes_formula_injection_characters(): void
    {
        $user = User::factory()->create();
        $event = Event::create([
            'user_id' => $user->id,
            'title' => 'Export Sanitization Event',
            'event_date' => now()->addDays(5)->format('Y-m-d'),
            'event_time' => '18:00:00',
            'venue' => 'Main Hall',
        ]);

        // Create guest directly in database with potential formula payload
        $guest = Guest::create([
            'event_id' => $event->id,
            'name' => '=SUM(1+1)',
            'email' => '@evil.com',
            'phone' => '+1234567890',
            'max_companions' => 2,
        ]);
        $guest->rsvp()->create([
            'status' => 'attending',
            'companions_count' => 1,
            'companion_name' => '-CompanionPayload',
            'message' => "=1+1;cmd|' /C calc'!A0",
            'responded_at' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('events.guests.export', $event));
        $response->assertStatus(200);

        ob_start();
        $response->sendContent();
        $csvContent = ob_get_clean();

        $this->assertStringContainsString("'=SUM(1+1)", $csvContent);
        $this->assertStringContainsString("'@evil.com", $csvContent);
        $this->assertStringContainsString("'+1234567890", $csvContent);
        $this->assertStringContainsString("'-CompanionPayload", $csvContent);
        $this->assertStringContainsString("'=1+1;cmd|' /C calc'!A0", $csvContent);
    }

    public function test_events_show_auto_opens_add_guest_modal_on_validation_error(): void
    {
        $user = User::factory()->create();
        $event = Event::create([
            'user_id' => $user->id,
            'title' => 'Modal Error Test Event',
            'event_date' => now()->addDays(5)->format('Y-m-d'),
            'event_time' => '18:00:00',
            'venue' => 'Main Hall',
        ]);

        // Submit invalid guest (empty name)
        $response = $this->actingAs($user)
            ->from(route('events.show', $event))
            ->post(route('events.guests.store', $event), [
                'name' => '',
            ]);

        $response->assertRedirect(route('events.show', $event));
        $response->assertSessionHasErrors('name');

        // Follow redirect to events.show
        $followResponse = $this->actingAs($user)->get(route('events.show', $event));
        $followResponse->assertStatus(200);
        $followResponse->assertSee('showAddGuestModal: true', false);
        $followResponse->assertSee('show: false', false);
        $followResponse->assertSee('The name field is required.', false);
    }

    public function test_event_rsvp_stats_endpoint_returns_accurate_stats(): void
    {
        $user = User::factory()->create();
        $event = Event::create([
            'user_id' => $user->id,
            'title' => 'Stats Test Event',
            'event_date' => now()->addDays(5)->format('Y-m-d'),
            'event_time' => '18:00:00',
            'venue' => 'Main Hall',
        ]);

        $guest1 = Guest::create([
            'event_id' => $event->id,
            'name' => 'Attending Guest',
            'email' => 'attending@example.com',
            'max_companions' => 2,
        ]);
        $guest1->rsvp()->create([
            'status' => 'attending',
            'companions_count' => 2,
        ]);

        $guest2 = Guest::create([
            'event_id' => $event->id,
            'name' => 'Declined Guest',
            'email' => 'declined@example.com',
            'max_companions' => 0,
        ]);
        $guest2->rsvp()->create([
            'status' => 'not_attending',
            'companions_count' => 0,
        ]);

        $guest3 = Guest::create([
            'event_id' => $event->id,
            'name' => 'Pending Guest',
            'email' => 'pending@example.com',
            'max_companions' => 1,
        ]);

        $response = $this->actingAs($user)->getJson(route('events.rsvp-stats', $event));

        $response->assertStatus(200)
            ->assertJson([
                'stats' => [
                    'total_invited' => 3,
                    'attending' => 1,
                    'not_attending' => 1,
                    'pending' => 1,
                    'total_headcount' => 3, // 1 guest + 2 companions
                ],
            ])
            ->assertJsonCount(3, 'guests');

        // Test unauthorized user cannot view stats
        $otherUser = User::factory()->create();
        $this->actingAs($otherUser)->getJson(route('events.rsvp-stats', $event))
            ->assertStatus(403);
    }

    public function test_dashboard_stats_endpoint_returns_user_aggregate_stats(): void
    {
        $user = User::factory()->create();
        $event1 = Event::create([
            'user_id' => $user->id,
            'title' => 'Event 1',
            'event_date' => now()->addDays(5)->format('Y-m-d'),
            'event_time' => '18:00:00',
            'venue' => 'Venue 1',
        ]);

        $guest1 = Guest::create([
            'event_id' => $event1->id,
            'name' => 'Guest 1',
            'max_companions' => 1,
        ]);
        $guest1->rsvp()->create([
            'status' => 'attending',
            'companions_count' => 1,
        ]);

        $response = $this->actingAs($user)->getJson(route('dashboard.stats'));

        $response->assertStatus(200)
            ->assertJson([
                'stats' => [
                    'total_events' => 1,
                    'total_invited' => 1,
                    'attending' => 1,
                    'not_attending' => 0,
                    'pending' => 0,
                    'total_headcount' => 2,
                ],
            ]);
    }
}



