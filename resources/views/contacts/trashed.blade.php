@extends('layouts.app')

@section('title', 'Corbeille')

@section('header-title', '🗑️ Corbeille')
@section('header-subtitle', 'Contacts supprimés (restauration possible)')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <p class="text-gray-500 text-sm">
            <span class="font-bold text-bleu-fonce">{{ $contacts->total() }}</span> contact(s) dans la corbeille
        </p>
        <a href="{{ route('contacts.index') }}" class="btn-outline-primary">
            <i class="fas fa-arrow-left mr-2"></i> Retour
        </a>
    </div>

    @if($contacts->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($contacts as $contact)
                <div class="card p-5 hover:shadow-xl transition-all duration-300 border-l-4 border-red-400 opacity-90">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-bleu-fonce">
                                {{ $contact->name }} {{ $contact->surname }}
                            </h3>
                            
                            <div class="mt-2 space-y-1 text-sm text-gray-600">
                                @if($contact->phone)
                                    <p><i class="fas fa-phone text-bleu-clair w-5"></i> {{ $contact->phone }}</p>
                                @endif
                                @if($contact->email)
                                    <p><i class="fas fa-envelope text-bleu-clair w-5"></i> {{ $contact->email }}</p>
                                @endif
                            </div>

                            <div class="mt-3 flex items-center gap-2">
                                <span class="badge-{{ $contact->group }} px-3 py-1 rounded-full text-xs font-medium">
                                    {{ $contact->group }}
                                </span>
                                <span class="text-xs text-gray-400">
                                    <i class="fas fa-clock mr-1"></i>
                                    Supprimé {{ $contact->deleted_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-100 flex justify-end gap-2">
                        <form action="{{ route('contacts.restore', $contact->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-green-600 hover:text-green-700 transition p-2" title="Restaurer">
                                <i class="fas fa-trash-restore"></i>
                            </button>
                        </form>
                        <form action="{{ route('contacts.force-delete', $contact->id) }}" method="POST" class="inline" 
                              onsubmit="return confirmDelete('Supprimer définitivement ce contact ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-700 transition p-2" title="Supprimer définitivement">
                                <i class="fas fa-skull"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $contacts->links() }}
        </div>
    @else
        <div class="card p-12 text-center">
            <div class="text-6xl text-gray-300 mb-4">
                <i class="fas fa-trash"></i>
            </div>
            <h3 class="text-xl font-bold text-bleu-fonce mb-2">Corbeille vide</h3>
            <p class="text-gray-500 mb-4">
                Aucun contact supprimé pour le moment.
            </p>
            <a href="{{ route('contacts.index') }}" class="btn-primary inline-block">
                <i class="fas fa-address-card mr-2"></i> Voir mes contacts
            </a>
        </div>
    @endif
</div>
@endsection