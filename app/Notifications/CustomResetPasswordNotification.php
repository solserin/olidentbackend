<?php
 
namespace App\Notifications;
 
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
 
class CustomResetPasswordNotification extends Notification
{
    use Queueable;
 
    public $token;
 
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }
 
    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }
 
    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->from('olident.sistema@gmail.com','SISTEMA OLIDENT')
                    ->greeting('Servicios OLIDENT')
                    ->subject("SISTEMA OLIDENT | Recuperación de contraseña")
                    ->line("Olvidó su contraseña? De click en el botón para actualizarla.")
                    ->action('Recuperar Contraseña', url('password/reset', $this->token))
                    ->line('Ignore este mensaje si no solicitó un reestablecimiento de contraseña');
    }
 
}