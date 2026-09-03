@extends('layouts.app')

@section('title', 'Détails de ' . $contact->name . ' ' . $contact->surname)

@section('header-title', 'Détails du contact')
@section('header-subtitle', $contact->name . ' ' . $contact->surname)

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="card overflow-hidden">
        <!-- En-tête -->
        <div class="bg-gradient-to-r from-bleu-fonce to-bleu-moyen px-6 py-8 text-white">
            <div class="flex items-start justify-between">
                <div>
                    <div class="flex items-center gap-3">
                        <h1 class="text-2xl font-bold">{{ $contact->name }} {{ $contact->surname }}</h1>
                        @if($contact->favoris)
                            <span class="bg-orange-fonce px-3 py-1 rounded-full text-sm font-medium">
                                <i class="fas fa-star mr-1"></i> Favori
                            </span>
                        @endif
                    </div>
                    <p class="text-blue-200 mt-1">
                        <span class="badge-{{ $contact->group }} px-3 py-1 rounded-full text-xs">
                            {{ $contact->group }}
                        </span>
                    </p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('contacts.edit', $contact->id) }}" class="bg-white/20 hover:bg-white/30 text-white p-2 rounded-lg transition" title="Modifier">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('contacts.toggle-favori', $contact->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-white/20 hover:bg-white/30 text-white p-2 rounded-lg transition" title="Mettre en favori">
                            <i class="{{ $contact->favoris ? 'fas fa-star text-yellow-300' : 'far fa-star' }}"></i>
                        </button>
                    </form>
                    <form action="{{ route('contacts.destroy', $contact->id) }}" method="POST" class="inline" onsubmit="return confirm('Voulez-vous vraiment supprimer ce contact ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500/20 hover:bg-red-500/30 text-white p-2 rounded-lg transition" title="Supprimer">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Informations -->
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-start gap-3 p-3 bg-blue-50 rounded-lg">
                    <i class="fas fa-phone text-bleu-clair text-lg mt-1"></i>
                    <div>
                        <p class="text-sm text-gray-500">Téléphone</p>
                        <p class="font-medium text-bleu-fonce">{{ $contact->phone ?? 'Non renseigné' }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-3 bg-blue-50 rounded-lg">
                    <i class="fas fa-envelope text-bleu-clair text-lg mt-1"></i>
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="font-medium text-bleu-fonce">{{ $contact->email ?? 'Non renseigné' }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-3 bg-orange-50 rounded-lg">
                    <i class="fas fa-birthday-cake text-orange-fonce text-lg mt-1"></i>
                    <div>
                        <p class="text-sm text-gray-500">Date de naissance</p>
                        <p class="font-medium text-bleu-fonce">
                            @if($contact->Birthday)
                                {{ $contact->Birthday->format('d/m/Y') }}
                                <span class="text-sm text-gray-500">({{ $contact->Birthday->age }} ans)</span>
                            @else
                                Non renseignée
                            @endif
                        </p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-3 bg-orange-50 rounded-lg">
                    <i class="fas fa-tag text-orange-fonce text-lg mt-1"></i>
                    <div>
                        <p class="text-sm text-gray-500">Groupe</p>
                        <p class="font-medium text-bleu-fonce">{{ $contact->group ?? 'Général' }}</p>
                    </div>
                </div>
            </div>

            @if($contact->adress)
                <div class="p-3 bg-blue-50 rounded-lg">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-map-marker-alt text-bleu-clair text-lg mt-1"></i>
                        <div>
                            <p class="text-sm text-gray-500">Adresse</p>
                            <p class="font-medium text-bleu-fonce">{{ $contact->adress }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if($contact->notes)
                <div class="p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-sticky-note text-gray-500 text-lg mt-1"></i>
                        <div>
                            <p class="text-sm text-gray-500">Notes</p>
                            <p class="text-bleu-fonce">{{ $contact->notes }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="text-xs text-gray-400 border-t border-gray-100 pt-4 mt-4">
                <p>Créé le {{ $contact->created_at ? $contact->created_at->format('d/m/Y à H:i') : '-' }}</p>
                <p>Modifié le {{ $contact->updated_at ? $contact->updated_at->format('d/m/Y à H:i') : '-' }}</p>
            </div>
        </div>

        <div class="px-6 pb-6 flex flex-wrap gap-3">
            <a href="{{ route('contacts.index') }}" class="btn-outline-primary">
                <i class="fas fa-arrow-left mr-2"></i> Retour
            </a>
            <a href="{{ route('contacts.edit', $contact->id) }}" class="btn-primary">
                <i class="fas fa-edit mr-2"></i> Modifier
            </a>
        </div>
    </div>
</div>
@endsection