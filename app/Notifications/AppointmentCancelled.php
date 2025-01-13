<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentCancelled extends Notification
{
    use Queueable;
    protected $reason;
    protected $appointment;

    /**
     * Create a new notification instance.
     */
    public function __construct($reason, $appointment)
    {
        $this->reason = $reason;
        $this->appointment = $appointment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
/*                     ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!'); */
                    ->subject('Cita Cancelada')
                    ->line('Estimado ' . $this->appointment->owner->name . ',')
                    ->line('Lamentamos informarle de que su cita ha sido cancelada. Mas abajo se detallan los motivos y los detalles de su cita.')
                    ->line('Motivo: ' . $this->reason)
                    ->line('Fecha: ' . Carbon::parse($this->appointment->date)->format('d/m/Y'))
                    ->line('Hora: ' . Carbon::parse($this->appointment->start_time)->format('H:i'))
                    ->line('Veterinario: ' . $this->appointment->vet->name)
                    ->line('Puede agendar una nueva cita a través de nuestra web o llamando a la clinica.')
                    ->line('Gracias por usar nuestro sistema.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
