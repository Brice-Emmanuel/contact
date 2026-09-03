<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;


class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // Total des contacts de l'utilisateur connecté
        $total = Contact::where('user_id', $userId)->count();

        // Statistiques
        $stats = [
            'total' => $total,

            'favoris' => Contact::where('user_id', $userId)
                ->where(function ($query) {
                    $query->where('favoris', true)
                          ->orWhere('favoris', 1);
                })
                ->count(),

            'avec_telephone' => Contact::where('user_id', $userId)
                ->whereNotNull('phone')
                ->where('phone', '!=', '')
                ->count(),

            'avec_email' => Contact::where('user_id', $userId)
                ->whereNotNull('email')
                ->where('email', '!=', '')
                ->count(),

            'supprimes' => Contact::where('user_id', $userId)->onlyTrashed()->count(),

            // Prise en compte des majuscules et minuscules
            'par_groupe' => [
                'famille'  => Contact::where('user_id', $userId)->whereIn('group', ['famille', 'Famille'])->count(),
                'amis'     => Contact::where('user_id', $userId)->whereIn('group', ['amis', 'Amis'])->count(),
                'Collègue' => Contact::where('user_id', $userId)->whereIn('group', ['Collègue', 'collègue', 'Collegue', 'collegue'])->count(),
                'autres'   => Contact::where('user_id', $userId)->whereIn('group', ['autres', 'Autres'])->count(),
            ],

            // Vérifiez si votre colonne d'anniversaire s'appelle Birthday ou birthday
            'anniversaires_aujourdhui' => Contact::where('user_id', $userId)
                ->whereNotNull('Birthday')
                ->whereMonth('Birthday', now()->month)
                ->whereDay('Birthday', now()->day)
                ->count(),

            'anniversaires_mois' => Contact::where('user_id', $userId)
                ->whereNotNull('Birthday')
                ->whereMonth('Birthday', now()->month)
                ->count(),
        ];

        // Calcul des pourcentages
        $stats['par_groupe_pourcentage'] = [];
        foreach ($stats['par_groupe'] as $groupe => $count) {
            $stats['par_groupe_pourcentage'][$groupe] = $total > 0 
                ? round(($count / $total) * 100, 1) 
                : 0;
        }

        // 5 derniers contacts
        $recents = Contact::where('user_id', $userId)
            ->latest()
            ->limit(5)
            ->get();

        // Correction : nom de vue 'dashboard' (avec un 'h')
        return view('dashboard', compact('stats', 'recents'));
    }
}