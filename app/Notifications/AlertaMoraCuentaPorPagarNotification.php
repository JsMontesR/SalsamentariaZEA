<?php

namespace App\Notifications;

use App\Entrada;
use App\Jobs\ActualizarNotificaciones;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AlertaMoraCuentaPorPagarNotification extends Notification
{
    use Queueable;

    protected $message;
    protected $entrada;

    /**
     * Create a new notification instance.
     *
     * @param Entrada $entrada
     * @param $message
     */
    public function __construct(Entrada $entrada, $message)
    {
        $this->message = $message;
        $this->entrada = $entrada;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            "id" => $this->entrada->id,
            "tipo" => ActualizarNotificaciones::ALERTA,
            "endpoint" => ActualizarNotificaciones::ENTRADA,
            "mensaje" => $this->message
        ];
    }
}
