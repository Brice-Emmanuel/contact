<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ContactController extends Controller
{
    /**
     * Appliquer le middleware d'authentification sur toutes les méthodes.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Liste des contacts avec filtres et recherche.
     */
    public function index(Request $request)
    {
        $query = Contact::where('user_id', auth()->id());

        if ($request->filled('groupe')) {
            $query->where('group', strtolower($request->groupe));
        }

        if ($request->has('favoris') && filter_var($request->favoris, FILTER_VALIDATE_BOOLEAN)) {
            $query->where('favoris', true);
        }

        if ($request->filled('recherche')) {
            $search = $request->recherche;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('surname', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('adress', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        $contacts = $query->orderBy('name')->paginate(12);

        return view('contacts.index', compact('contacts'));
    }

    /**
     * Formulaire de création.
     */
    public function create()
    {
        return view('contacts.create');
    }

    /**
     * Enregistrement d'un nouveau contact.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'surname'  => 'required|string|max:100',
            'email'    => [
                'nullable',
                'email',
                Rule::unique('contacts')->where(fn ($q) => $q->where('user_id', auth()->id()))
            ],
            'adress'   => 'nullable|string|max:255',
            'phone'    => [
                'required',
                'string',
                'max:100',
                Rule::unique('contacts')->where(fn ($q) => $q->where('user_id', auth()->id()))
            ],
            'group'    => 'nullable|in:famille,amis,collègue,autres',
            'favoris'  => 'nullable|boolean',
            'Birthday' => 'nullable|date',
            'notes'    => 'nullable|string|max:255'
        ], [
            'name.required'    => 'Veuillez entrer votre nom.',
            'surname.required' => 'Veuillez entrer votre prénom.',
            'phone.required'   => 'Veuillez entrer votre numéro.',
            'email.email'      => 'Veuillez entrer une adresse email valide.',
            'phone.unique'     => 'Ce numéro de téléphone existe déjà dans vos contacts.',
            'email.unique'     => 'Cet email existe déjà dans vos contacts.',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['favoris'] = $request->has('favoris');

        Contact::create($validated);

        return redirect()->route('contacts.index')
            ->with('success', 'Contact créé avec succès !');
    }

    /**
     * Affichage d'un contact spécifique.
     */
    public function show($id)
    {
        $contact = Contact::where('user_id', auth()->id())->findOrFail($id);
        return view('contacts.show', compact('contact'));
    }

    /**
     * Formulaire d'édition.
     */
    public function edit($id)
    {
        $contact = Contact::where('user_id', auth()->id())->findOrFail($id);
        return view('contacts.edit', compact('contact'));
    }

    /**
     * Mise à jour du contact.
     */
    public function update(Request $request, $id)
    {
        $contact = Contact::where('user_id', auth()->id())->findOrFail($id);

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'surname'  => 'required|string|max:100',
            'email'    => [
                'nullable',
                'email',
                Rule::unique('contacts')->where(fn ($q) => $q->where('user_id', auth()->id())->where('id', '!=', $id))
            ],
            'adress'   => 'nullable|string|max:255',
            'phone'    => [
                'required',
                'string',
                'max:100',
                Rule::unique('contacts')->where(fn ($q) => $q->where('user_id', auth()->id())->where('id', '!=', $id))
            ],
            'group'    => 'nullable|in:famille,amis,collègue,autres',
            'favoris'  => 'nullable|boolean',
            'Birthday' => 'nullable|date',
            'notes'    => 'nullable|string|max:255'
        ], [
            'name.required'    => 'Veuillez entrer votre nom.',
            'surname.required' => 'Veuillez entrer votre prénom.',
            'phone.required'   => 'Veuillez entrer votre numéro.',
            'email.email'      => 'Veuillez entrer une adresse email valide.',
            'phone.unique'     => 'Ce numéro de téléphone existe déjà dans vos contacts.',
            'email.unique'     => 'Cet email existe déjà dans vos contacts.',
        ]);

        $validated['favoris'] = $request->has('favoris');
        $contact->update($validated);

        return redirect()->route('contacts.index')
            ->with('success', 'Contact mis à jour avec succès !');
    }

    /**
     * Soft Delete (Envoi vers la corbeille).
     */
    public function destroy($id)
    {
        $contact = Contact::where('user_id', auth()->id())->findOrFail($id);
        $contact->delete();

        return redirect()->route('contacts.index')
            ->with('success', 'Contact déplacé dans la corbeille.');
    }

    /**
     * Affichage des favoris.
     */
    public function favoris()
    {
        $contacts = Contact::where('user_id', auth()->id())
            ->where('favoris', true)
            ->orderBy('name')
            ->paginate(12);

        return view('contacts.favoris', compact('contacts'));
    }

    /**
     * Basculer l'état "favoris".
     */
    public function toggleFavori($id)
    {
        $contact = Contact::where('user_id', auth()->id())->findOrFail($id);
        $contact->favoris = !$contact->favoris;
        $contact->save();

        $message = $contact->favoris ? 'Ajouté aux favoris !' : 'Retiré des favoris.';

        return redirect()->back()->with('success', $message);
    }

    /**
     * Corbeille.
     */
    public function trashed()
    {
        $contacts = Contact::where('user_id', auth()->id())
            ->onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->paginate(12);

        return view('contacts.trashed', compact('contacts'));
    }

    /**
     * Restauration d'un contact supprimé.
     */
    public function restore($id)
    {
        $contact = Contact::where('user_id', auth()->id())
            ->onlyTrashed()
            ->findOrFail($id);
        
        $contact->restore();

        return redirect()->route('contacts.trashed')
            ->with('success', 'Contact restauré avec succès !');
    }

    /**
     * Suppression définitive.
     */
    public function forceDelete($id)
    {
        $contact = Contact::where('user_id', auth()->id())
            ->onlyTrashed()
            ->findOrFail($id);

        $contact->forceDelete();

        return redirect()->route('contacts.trashed')
            ->with('success', 'Contact supprimé définitivement.');
    }

    /**
     * Anniversaires du jour.
     */
    public function anniversairesAujourdhui()
    {
        $contacts = Contact::where('user_id', auth()->id())
            ->whereMonth('Birthday', now()->month)
            ->whereDay('Birthday', now()->day)
            ->get();

        return view('contacts.index', compact('contacts'))
            ->with('success', count($contacts) . ' contact(s) fêtent leur anniversaire aujourd\'hui !');
    }

    /**
     * Prochains anniversaires (7 jours par défaut).
     */
    public function prochainsAnniversaires(Request $request)
    {
        $jours = (int) $request->get('jours', 7);

        $contacts = Contact::where('user_id', auth()->id())
            ->whereNotNull('Birthday')
            ->get()
            ->filter(function ($contact) use ($jours) {
                $birthday = Carbon::parse($contact->Birthday)->setYear(now()->year);
                if ($birthday->isPast() && !$birthday->isToday()) {
                    $birthday->addYear();
                }
                return $birthday->between(now()->startOfDay(), now()->addDays($jours)->endOfDay());
            });

        return view('contacts.index', compact('contacts'))
            ->with('success', count($contacts) . ' contact(s) fêtent leur anniversaire dans les ' . $jours . ' jours.');
    }

    /**
     * Tableau de bord / Statistiques.
     */
    public function stats()
    {
        $userId = auth()->id();
        $total = Contact::where('user_id', $userId)->count();

        $stats = [
            'total'          => $total,
            'favoris'        => Contact::where('user_id', $userId)->where('favoris', true)->count(),
            'avec_telephone' => Contact::where('user_id', $userId)->whereNotNull('phone')->count(),
            'avec_email'     => Contact::where('user_id', $userId)->whereNotNull('email')->count(),
            'supprimes'      => Contact::where('user_id', $userId)->onlyTrashed()->count(),
            'par_groupe'     => [
                'famille'  => Contact::where('user_id', $userId)->where('group', 'famille')->count(),
                'amis'     => Contact::where('user_id', $userId)->where('group', 'amis')->count(),
                'collègue' => Contact::where('user_id', $userId)->where('group', 'collègue')->count(),
                'autres'   => Contact::where('user_id', $userId)->where('group', 'autres')->count(),
            ],
            'anniversaires_aujourdhui' => Contact::where('user_id', $userId)
                ->whereMonth('Birthday', now()->month)
                ->whereDay('Birthday', now()->day)
                ->count(),
            'anniversaires_mois' => Contact::where('user_id', $userId)
                ->whereMonth('Birthday', now()->month)
                ->count(),
        ];

        $stats['par_groupe_pourcentage'] = [];
        foreach ($stats['par_groupe'] as $groupe => $count) {
            $stats['par_groupe_pourcentage'][$groupe] = $total > 0 
                ? round(($count / $total) * 100, 1) 
                : 0;
        }

        $recents = Contact::where('user_id', $userId)
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard', compact('stats', 'recents'));
    }

    /**
     * Export des contacts au format CSV.
     */
    public function export()
    {
        $contacts = Contact::where('user_id', auth()->id())->get();

        $csv = "Nom;Prénom;Email;Téléphone;Adresse;Groupe;Favori;Date naissance;Notes\n";

        foreach ($contacts as $contact) {
            $csv .= implode(';', [
                $contact->name,
                $contact->surname ?? '',
                $contact->email ?? '',
                $contact->phone ?? '',
                $contact->adress ?? '',
                $contact->group ?? 'autres',
                $contact->favoris ? 'Oui' : 'Non',
                $contact->Birthday ? Carbon::parse($contact->Birthday)->format('d/m/Y') : '',
                str_replace(["\n", "\r", ";"], ' ', $contact->notes ?? ''),
            ]) . "\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="contacts_' . date('Y-m-d') . '.csv"');
    }

    /**
     * Importation de contacts CSV.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('file');
        $handle = fopen($file->getPathname(), 'r');

        $headers = fgetcsv($handle, 1000, ';');

        if (!$headers) {
            return redirect()->back()->with('error', 'Fichier CSV invalide ou vide.');
        }

        $map = [
            'name'     => array_search('Nom', $headers),
            'surname'  => array_search('Prénom', $headers),
            'email'    => array_search('Email', $headers),
            'phone'    => array_search('Téléphone', $headers),
            'adress'   => array_search('Adresse', $headers),
            'group'    => array_search('Groupe', $headers),
            'favoris'  => array_search('Favori', $headers),
            'Birthday' => array_search('Date naissance', $headers),
            'notes'    => array_search('Notes', $headers),
        ];

        $importes = 0;

        while (($row = fgetcsv($handle, 1000, ';')) !== false) {
            try {
                if ($map['name'] === false || empty($row[$map['name']])) {
                    continue;
                }

                $data = [
                    'user_id' => auth()->id(),
                    'name'    => trim($row[$map['name']]),
                    'surname' => $map['surname'] !== false ? trim($row[$map['surname']] ?? '') : null,
                    'email'   => $map['email'] !== false ? trim($row[$map['email']] ?? '') : null,
                    'phone'   => $map['phone'] !== false ? trim($row[$map['phone']] ?? '') : null,
                    'adress'  => $map['adress'] !== false ? trim($row[$map['adress']] ?? '') : null,
                    'favoris' => $map['favoris'] !== false && in_array(strtolower(trim($row[$map['favoris']] ?? '')), ['oui', 'true', '1']),
                    'notes'   => $map['notes'] !== false ? trim($row[$map['notes']] ?? '') : null,
                ];

                if ($map['group'] !== false && !empty($row[$map['group']])) {
                    $groupe = strtolower(trim($row[$map['group']]));
                    $data['group'] = in_array($groupe, ['famille', 'amis', 'collègue', 'autres']) 
                        ? $groupe 
                        : 'autres';
                } else {
                    $data['group'] = 'autres';
                }

                if ($map['Birthday'] !== false && !empty($row[$map['Birthday']])) {
                    try {
                        $data['Birthday'] = Carbon::createFromFormat('d/m/Y', trim($row[$map['Birthday']]))->format('Y-m-d');
                    } catch (\Exception $e) {
                        $data['Birthday'] = null;
                    }
                }

                Contact::create($data);
                $importes++;

            } catch (\Exception $e) {
                continue;
            }
        }

        fclose($handle);

        return redirect()->route('contacts.index')
            ->with('success', $importes . ' contacts importés avec succès !');
    }
}