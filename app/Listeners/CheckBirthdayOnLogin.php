<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\BirthdayWishNotification;
use Illuminate\Auth\Events\Login;
use Carbon\Carbon;

class CheckBirthdayOnLogin
{
    public function handle(Login $event): void
    {
        /** @var User $user */
        $user = $event->user;

        if (!$user instanceof User) {
            return;
        }

        $today = Carbon::today();

        // Récupérer tous les contacts de l'utilisateur qui fêtent leur anniversaire aujourd'hui
        $birthdayContacts = $user->contacts()
            ->whereNotNull('Birthday')
            ->whereMonth('Birthday', $today->month)
            ->whereDay('Birthday', $today->day)
            ->get();

        // S'il y a des anniversaires aujourd'hui et qu'on ne l'a pas encore notifié cette année/jour
        if ($birthdayContacts->isNotEmpty()) {
            
            // Éviter de renvoyer si l'utilisateur s'est déjà connecté aujourd'hui
            if ($user->last_birthday_wish_year == $today->format('Y-m-d')) {
                return;
            }

            // Envoi de la notification en lui passant la liste des contacts
            $user->notify(new BirthdayWishNotification($birthdayContacts));

            // On enregistre la date du dernier envoi (ou l'année)
            $user->update([
                'last_birthday_wish_year' => $today->format('Y-m-d'),
            ]);
        }
    }
}