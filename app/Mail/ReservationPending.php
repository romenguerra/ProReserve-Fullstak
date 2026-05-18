<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationPending extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tu Reserva está Pendiente de Confirmación - ProReserve',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reservation_pending',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
