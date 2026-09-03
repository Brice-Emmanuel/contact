@extends('layouts.app')

@section('title', 'Contacts favoris')

@section('header-title', '⭐ Contacts favoris')
@section('header-subtitle', 'Vos contacts préférés')

@section('content')
<div class="space-y-6">
    @if($contacts->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($contacts as $contact)
                <div class="card p-5 hover:shadow-xl transition-all duration-300 border-l-4 border-orange-fonce">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-star text-orange-fonce"></i>
                                <h3 class="text-lg font-bold text-bleu-fonce">
                                    <a href="{{ route('contacts.show', $contact->id) }}" class="hover:text-orange-fonce transition">
                                        {{ $contact->name }} {{ $contact->surname }}
                                    </a>
                                </h3>
                            </div>
                            
                            <div class="mt-2 space-y-1 text-sm text-gray-600">
                                @if($contact->phone)
                                    <p><i class="fas fa-phone text-bleu-clair w-5"></i> {{ $contact->phone }}</p>
                                @endif
                                @if($contact->email)
                                    <p><i class="fas fa-envelope text-bleu-clair w-5"></i> {{ $contact->email }}</p>
                                @endif
                            </div>

                            <div class="mt-3">
                                <span class="badge-{{ $contact->group }} px-3 py-1 rounded-full text-xs font-medium">
                                    {{ $contact->group }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-100 flex justify-end gap-2">
                        <a href="{{ route('contacts.show', $contact->id) }}" 
                           class="text-bleu-clair hover:text-bleu-fonce transition p-2">
                            <i class="fas fa-eye"></i>
                        </a>
                        <form action="{{ route('contacts.toggle-favori', $contact->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-orange-fonce hover:text-orange-moyen transition p-2"
                                    title="Retirer des favoris">
                                <i class="fas fa-star"></i>
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
                <i class="fas fa-star"></i>
            </div>
            <h3 class="text-xl font-bold text-bleu-fonce mb-2">Aucun favori</h3>
            <p class="text-gray-500 mb-4">
                Vous n'avez pas encore de contacts favoris.
            </p>
            <a href="{{ route('contacts.index') }}" class="btn-primary inline-block">
                <i class="fas fa-arrow-left mr-2"></i> Voir tous les contacts
            </a>
        </div>
    @endif
</div>
@endsection