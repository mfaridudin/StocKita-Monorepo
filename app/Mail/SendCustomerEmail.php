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

    public function __construct($customer)
    {
        $this->customer = $customer;

        $variables = [
            '{{name}}' => $customer->user->name,
            '{{store.name}}' => setting('store.name'),
        ];

        $template = setting('email.welcome');

        foreach ($variables as $key => $value) {
            $template = str_replace($key, $value, $template);
        }

        $this->contentData = $template;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pesan dari '.setting('store.name'),
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
