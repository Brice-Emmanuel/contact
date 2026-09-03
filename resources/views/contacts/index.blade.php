@extends('layouts.app')

@section('title', 'Mes contacts')

@section('header-title', 'Mes contacts')
@section('header-subtitle', 'Gérez tous vos contacts en un seul endroit')

@section('content')
<div class="space-y-6">
    <!-- Filtres -->
    <div class="card p-4">
        <form method="GET" action="{{ route('contacts.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-[200px]">
                <div class="relative">
                    <input type="text" name="recherche" placeholder="Rechercher un contact..." 
                           value="{{ request('recherche') }}" class="form-input pl-10">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>

            <div class="w-full sm:w-auto">
                <select name="groupe" class="form-input bg-white">
                    <option value="">Tous les groupes</option>
                    <option value="famille" {{ request('groupe') == 'famille' ? 'selected' : '' }}>🏠 Famille</option>
                    <option value="amis" {{ request('groupe') == 'amis' ? 'selected' : '' }}>🤝 Amis</option>
                    <option value="Collègue" {{ request('groupe') == 'Collègue' ? 'selected' : '' }}>💼 Collègue</option>
                    <option value="autres" {{ request('groupe') == 'autres' ? 'selected' : '' }}>📌 Autres</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="favoris" value="true" id="favoris"
                       {{ request('favoris') == 'true' ? 'checked' : '' }}
                       class="w-4 h-4 text-orange-fonce rounded border-gray-300 focus:ring-orange-fonce">
                <label for="favoris" class="text-sm text-bleu-fonce font-medium cursor-pointer">
                    <i class="fas fa-star text-orange-fonce"></i> Favoris uniquement
                </label>
            </div>

            <button type="submit" class="btn-primary px-6">
                <i class="fas fa-filter"></i> Filtrer
            </button>

            @if(request()->hasAny(['recherche', 'groupe', 'favoris']))
                <a href="{{ route('contacts.index') }}" class="text-sm text-gray-500 hover:text-orange-fonce">
                    <i class="fas fa-times"></i> Réinitialiser
                </a>
            @endif
        </form>
    </div>

    <!-- Actions -->
    <div class="flex justify-between items-center">
        <p class="text-gray-500 text-sm">
            <span class="font-bold text-bleu-fonce">{{ $contacts->total() }}</span> contact(s) trouvé(s)
        </p>
        <div class="flex gap-2">
            <a href="{{ route('contacts.export') }}" class="btn-outline-primary">
                <i class="fas fa-file-export mr-2"></i> Exporter
            </a>
            <a href="{{ route('contacts.create') }}" class="btn-orange">
                <i class="fas fa-plus mr-2"></i> Ajouter
            </a>
        </div>
    </div>

    <!-- Liste -->
    @if($contacts->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($contacts as $contact)
                <div class="card p-5 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <h3 class="text-lg font-bold text-bleu-fonce">
                                    <a href="{{ route('contacts.show', $contact->id) }}" class="hover:text-orange-fonce transition">
                                        {{ $contact->name }} {{ $contact->surname }}
                                    </a>
                                </h3>
                                @if($contact->favoris)
                                    <i class="fas fa-star text-orange-fonce"></i>
                                @endif
                            </div>
                            
                            <div class="mt-2 space-y-1 text-sm text-gray-600">
                                @if($contact->phone)
                                    <p><i class="fas fa-phone text-bleu-clair w-5"></i> {{ $contact->phone }}</p>
                                @endif
                                @if($contact->email)
                                    <p><i class="fas fa-envelope text-bleu-clair w-5"></i> {{ $contact->email }}</p>
                                @endif
                            </div>

                            <div class="mt-3 flex flex-wrap items-center gap-2">
                                <span class="badge-{{ $contact->group }} px-3 py-1 rounded-full text-xs font-medium">
                                    {{ $contact->group }}
                                </span>
                                @if($contact->Birthday)
                                    <span class="bg-gray-100 px-3 py-1 rounded-full text-xs text-gray-600">
                                        <i class="fas fa-birthday-cake mr-1"></i> 
                                        {{ \Carbon\Carbon::parse($contact->Birthday)->age }} ans
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-100 flex justify-end gap-2">
                        <a href="{{ route('contacts.show', $contact->id) }}" 
                           class="text-bleu-clair hover:text-bleu-fonce transition p-2">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('contacts.edit', $contact->id) }}" 
                           class="text-orange-fonce hover:text-orange-moyen transition p-2">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('contacts.toggle-favori', $contact->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-400 hover:text-orange-fonce transition p-2"
                                    title="{{ $contact->favoris ? 'Retirer des favoris' : 'Ajouter aux favoris' }}">
                                <i class="fas {{ $contact->favoris ? 'fa-star text-orange-fonce' : 'fa-star' }}"></i>
                            </button>
                        </form>
                        <form action="{{ route('contacts.destroy', $contact->id) }}" method="POST" class="inline" onsubmit="return confirmDelete()">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-gray-400 hover:text-red-600 transition p-2">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $contacts->withQueryString()->links() }}
        </div>
    @else
        <div class="card p-12 text-center">
            <div class="text-6xl text-gray-300 mb-4">
                <i class="fas fa-address-book"></i>
            </div>
            <h3 class="text-xl font-bold text-bleu-fonce mb-2">Aucun contact trouvé</h3>
            <p class="text-gray-500 mb-4">
                @if(request()->hasAny(['recherche', 'groupe', 'favoris']))
                    Aucun contact ne correspond à vos filtres.
                @else
                    Commencez à ajouter vos premiers contacts !
                @endif
            </p>
            <a href="{{ route('contacts.create') }}" class="btn-orange inline-block">
                <i class="fas fa-plus mr-2"></i> Ajouter un contact
            </a>
        </div>
    @endif
</div>
@endsection