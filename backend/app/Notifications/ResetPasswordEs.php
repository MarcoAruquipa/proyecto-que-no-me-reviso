<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordEs extends ResetPassword
{
    public function toMail($notifiable)
    {
        $minutos = config('auth.passwords.' . config('auth.defaults.passwords') . '.expire');

        return (new MailMessage)
            ->subject('Recuperación de contraseña')
            ->greeting('Hola,')
            ->line('Recibimos una solicitud para restablecer la contraseña de tu cuenta.')
            ->action('Restablecer contraseña', $this->resetUrl($notifiable))
            ->line("Este enlace expira en {$minutos} minutos y solo puede usarse una vez.")
            ->line('Si tú no solicitaste este cambio, puedes ignorar este mensaje y tu contraseña seguirá siendo la misma.');
    }
}
