<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEnquiryRequest;
use App\Mail\EnquiryReceived;
use App\Models\Enquiry;
use App\Models\SeoSetting;
use App\Support\SiteProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Throwable;

/**
 * Public enquiry endpoint used by every form on the website.
 *
 * A successful submission stores the enquiry and returns a flag the frontend
 * uses to fire the GA4 "generate_lead" event. A failed validation never
 * produces a lead event.
 */
class ContactController extends Controller
{
    public function __construct(private readonly SiteProfile $profile) {}

    public function store(StoreEnquiryRequest $request): RedirectResponse|JsonResponse
    {
        $throttleKey = 'enquiry:'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            throw ValidationException::withMessages([
                'phone' => 'Too many enquiries from this connection. Please try again later.',
            ]);
        }

        // Simple honeypot: real visitors never fill this hidden field.
        if (filled($request->input('website'))) {
            abort(422, 'Invalid submission.');
        }

        $data = $request->validated();

        RateLimiter::hit($throttleKey, 600);

        /*
         * Duplicate submission prevention: the same phone number with the same
         * requirement within ten minutes is treated as a double click / double
         * submit. The visitor still gets the success message, but only one
         * enquiry row (and one lead event) is created.
         */
        $fingerprint = 'enquiry:fingerprint:'.sha1(implode('|', [
            preg_replace('/\D/', '', (string) $data['phone']),
            mb_strtolower(trim((string) ($data['message'] ?? ''))),
            (string) ($data['source_url'] ?? ''),
        ]));

        if (! Cache::add($fingerprint, true, now()->addMinutes(10))) {
            return $this->successResponse($request, null);
        }

        $enquiry = Enquiry::query()->create([
            ...$data,
            'status' => 'new',
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 1000),
        ]);

        $this->notifyAdmin($enquiry);

        return $this->successResponse($request, $enquiry->getKey());
    }

    /**
     * Email every new enquiry to the business.
     *
     * A mail failure must never lose the enquiry or show the visitor an error,
     * so the exception is logged and the visitor still sees the success state.
     */
   private function notifyAdmin(Enquiry $enquiry): void
{
    $recipients = [
        'rinkusingh51998@gmail.com',
        'prajapatianuj366@gmail.com',
    ];

    try {
        Mail::to($recipients)->send(new EnquiryReceived($enquiry));
    } catch (Throwable $exception) {
        Log::error('Enquiry notification email failed: '.$exception->getMessage(), [
            'enquiry_id' => $enquiry->getKey(),
        ]);
    }
}

    /**
     * Same success response for a new enquiry and a duplicate submission.
     */
    private function successResponse(StoreEnquiryRequest $request, ?int $enquiryId): RedirectResponse|JsonResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'id' => $enquiryId,
                'message' => 'Thank you! Your enquiry has been received.',
            ], 201);
        }

        return back()->with('enquiry_success', 'Thank you! Your enquiry has been received. Our team will contact you shortly.');
    }
}
