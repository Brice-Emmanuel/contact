<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;

class BirthdayWishNotification extends Notification
{
    use Queueable;

    protected Collection $contacts;

    public function __construct(Collection $contacts)
    {
        $this->contacts = $contacts;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('🎂 Rappel d\'anniversaire(s) aujourd\'hui !')
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Voici les contacts qui fêtent leur anniversaire aujourd'hui :");

        foreach ($this->contacts as $contact) {
            $mail->line("🎉 **{$contact->surname} {$contact->name}** (" . ($contact->phone ?? 'Pas de numéro') . ")");
        }

        return $mail->action('Voir mes contacts', url('/contacts'));
    }
}