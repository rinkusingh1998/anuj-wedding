<?php

namespace App\Mail;

use App\Models\Enquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Internal notification sent to the business when a new enquiry arrives.
 *
 * This is a transactional notification, not a marketing email, so it is sent
 * immediately (not queued) and every detail of the enquiry is included.
 */
class EnquiryReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Enquiry $enquiry) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New website enquiry — '.$this->enquiry->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.enquiry-received',
            with: ['enquiry' => $this->enquiry],
        );
    }
}
