<?php

namespace App\Notifications;

use App\Jobs\ActualizarNotificacionesYAlertas;
use App\Venta;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AlertaMoraFacturaPorCobrarNotification extends Notification
{
    use Queueable;

    protected $message;
    protected $entrada;

    /**
     * Create a new notification instance.
     *
     * @param Venta $venta
     * @param $message
     */
    public function __construct(Venta $venta, $message)
    {
        $this->message = $message;
        $this->venta = $venta;
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
            "id" => $this->venta->id,
            "tipo" => ActualizarNotificacionesYAlertas::ALERTA,
            "endpoint" => ActualizarNotificacionesYAlertas::VENTA,
            "mensaje" => $this->message
        ];
    }
}
