<?php

namespace App\Mail;

use App\Models\BloodRequest;
use App\Models\Donor;
use App\Models\Hospital;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BloodRequestNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Donor $donor,
        public BloodRequest $bloodRequest,
        public Hospital $hospital,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Blood Request: {$this->bloodRequest->blood_group} needed at {$this->hospital->name}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.blood_request_notification',
        );
    }
}
