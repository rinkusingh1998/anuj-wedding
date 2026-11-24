<?php

namespace Tests\Feature;

use App\Filament\Resources\Enquiries\Pages\EditEnquiry;
use App\Filament\Resources\Enquiries\Pages\ListEnquiries;
use App\Mail\EnquiryReceived;
use App\Models\BusinessProfile;
use App\Models\Enquiry;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Public enquiry forms and the admin Enquiries table.
 */
class EnquiryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, string>
     */
    private function payload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Rohit Sharma',
            'phone' => '+91 98765 43210',
            'email' => 'rohit@example.com',
            'service' => 'Wedding Decoration',
            'event_type' => 'Wedding',
            'event_date' => '2026-12-10',
            'event_time' => '18:30',
            'city' => 'Meerut',
            'event_address' => 'Civil Lines, Meerut',
            'budget' => '₹50,000 – ₹1,00,000',
            'message' => 'Need stage and entry decoration.',
            'source_page' => 'contact',
            'source_url' => 'http://localhost/contact-us',
        ], $overrides);
    }

    public function test_an_enquiry_stores_every_field_and_emails_the_admin(): void
    {
        Mail::fake();

        config(['website.enquiry_notification_email' => 'owner@example.com']);

        $this->post('/enquiry', $this->payload())
            ->assertRedirect()
            ->assertSessionHas('enquiry_success');

        $this->assertDatabaseHas('enquiries', [
            'name' => 'Rohit Sharma',
            'phone' => '+91 98765 43210',
            'service' => 'Wedding Decoration',
            'event_type' => 'Wedding',
            'event_date' => '2026-12-10',
            'event_time' => '18:30',
            'city' => 'Meerut',
            'event_address' => 'Civil Lines, Meerut',
            'source_page' => 'contact',
            'status' => 'new',
        ]);

        $enquiry = Enquiry::query()->firstOrFail();

        Mail::assertSent(
            EnquiryReceived::class,
            fn (EnquiryReceived $mail): bool => $mail->enquiry->is($enquiry)
                && $mail->hasTo('owner@example.com'),
        );
    }

    public function test_enquiry_notification_falls_back_to_the_business_profile_email(): void
    {
        Mail::fake();

        config(['website.enquiry_notification_email' => null]);

        BusinessProfile::query()->create([
            'business_name' => 'Anuj Kumar Wedding Event Planners',
            'email' => 'owner@example.com',
        ]);

        $this->post('/enquiry', $this->payload())->assertRedirect();

        Mail::assertSent(
            EnquiryReceived::class,
            fn (EnquiryReceived $mail): bool => $mail->hasTo('owner@example.com'),
        );
    }

    public function test_the_enquiry_form_requires_the_same_fields_everywhere(): void
    {
        $this->post('/enquiry', [])
            ->assertSessionHasErrors([
                'name',
                'phone',
                'service',
                'event_type',
                'event_date',
                'event_time',
                'city',
                'event_address',
            ]);

        $this->assertDatabaseCount('enquiries', 0);
    }

    public function test_admin_can_filter_enquiries_by_status_service_city_and_source(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get('/admin/enquiries')->assertOk();

        Service::factory()->create(['name' => 'Wedding Decoration', 'slug' => 'wedding-decoration']);
        Service::factory()->create(['name' => 'Haldi Decoration', 'slug' => 'haldi-decoration']);

        $matching = Enquiry::query()->create($this->payload([
            'status' => 'new',
            'service' => 'Wedding Decoration',
            'city' => 'Meerut',
            'source_page' => 'contact',
        ]));

        $other = Enquiry::query()->create($this->payload([
            'name' => 'Another Customer',
            'phone' => '9999999999',
            'status' => 'closed',
            'service' => 'Haldi Decoration',
            'event_date' => '2027-01-15',
            'city' => 'Agra',
            'source_page' => 'home',
        ]));

        Livewire::test(ListEnquiries::class)
            ->filterTable('status', 'new')
            ->assertCanSeeTableRecords([$matching])
            ->assertCanNotSeeTableRecords([$other]);

        Livewire::test(ListEnquiries::class)
            ->filterTable('service', 'Wedding Decoration')
            ->assertCanSeeTableRecords([$matching])
            ->assertCanNotSeeTableRecords([$other]);

        Livewire::test(ListEnquiries::class)
            ->filterTable('city', 'Meerut')
            ->assertCanSeeTableRecords([$matching])
            ->assertCanNotSeeTableRecords([$other]);

        Livewire::test(ListEnquiries::class)
            ->filterTable('source_page', 'contact')
            ->assertCanSeeTableRecords([$matching])
            ->assertCanNotSeeTableRecords([$other]);

        Livewire::test(ListEnquiries::class)
            ->filterTable('event_between', [
                'event_from' => '2026-12-01',
                'event_until' => '2026-12-31',
            ])
            ->assertCanSeeTableRecords([$matching])
            ->assertCanNotSeeTableRecords([$other]);
    }

    public function test_admin_can_search_enquiries_and_change_the_status(): void
    {
        $this->actingAs(User::factory()->create());

        $matching = Enquiry::query()->create($this->payload([
            'name' => 'Rohit Sharma',
            'phone' => '9876543210',
            'service' => 'Wedding Decoration',
        ]));

        $other = Enquiry::query()->create($this->payload([
            'name' => 'Sneha Verma',
            'phone' => '9999999999',
            'service' => 'Birthday Decoration',
        ]));

        Livewire::test(ListEnquiries::class)
            ->searchTable('Rohit')
            ->assertCanSeeTableRecords([$matching])
            ->assertCanNotSeeTableRecords([$other]);

        Livewire::test(EditEnquiry::class, ['record' => $matching->getRouteKey()])
            ->fillForm(['status' => 'contacted'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame('contacted', $matching->refresh()->status);
    }
}
