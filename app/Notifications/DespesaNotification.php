<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DespesaNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $despesa;

    /**
     * Create a new notification instance.
     */
    public function __construct($despesa)
    {
        $this->despesa = $despesa;
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
            ->subject('Despesa Cadastrada')
            ->line('Uma nova despesa foi cadastrada.')
            ->line('Descrição: ' . $this->despesa->descricao)
            ->line('Valor: ' . $this->despesa->valor);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'despesa_id' => $this->despesa->id,
            'descricao' => $this->despesa->descricao,
            'valor' => $this->despesa->valor,
        ];
    }
}
