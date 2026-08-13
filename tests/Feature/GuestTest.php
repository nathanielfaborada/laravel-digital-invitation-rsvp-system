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
}
