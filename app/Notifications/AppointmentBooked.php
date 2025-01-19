<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentBooked extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct($appointment)
    {
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
                    ->subject('Cita Agendada')
                    ->line('Estimado ' . $this->appointment->owner->name . ',')
                    ->line('Le confirmamos que su cita ha sido agendada correctamente.')
                    ->line('Los detalles de su cita son los siguientes:')
                    ->line('Fecha: ' . Carbon::parse($this->appointment->date)->format('d/m/Y'))
                    ->line('Hora: ' . Carbon::parse($this->appointment->start_time)->format('H:i'))
                    ->line('Veterinario: ' . $this->appointment->vet->name)
                    ->line('Mascota: ' . $this->appointment->pet_name)
                    ->line('Por favor, le pedimos que intente acudir a la cita con 10 minutos de antelación. Si por cualquier motivo no puede asistir, le rogamos que nos lo comunique con la mayor antelación posible.')
                    ->line('Muchas gracias por confiar en nosotros.');
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
