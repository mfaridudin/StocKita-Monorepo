<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendCustomerEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $customer;
    public $contentData;
    public $subjectText;

    public function __construct($customer)
    {
        $this->customer = $customer;

        $email = email_template('welcome_email');

        $template = $email?->body ?? 'Halo {{ name }}';
        $subject = $email?->subject ?? 'Pesan';

        $data = [
            'name' => $customer->user->name,
            'store_name' => optional($customer->store)->name ?? 'Toko',
        ];

        $this->contentData = parse_template($template, $data);
        $this->subjectText = parse_template($subject, $data);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subjectText,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.dynamic',
            with: [
                'content' => $this->contentData,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
