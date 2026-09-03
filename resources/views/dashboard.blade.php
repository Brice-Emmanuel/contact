@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('header-title', 'Tableau de bord')
@section('header-subtitle', 'Vue d\'ensemble de vos contacts')

@section('content')
<div class="space-y-6 w-full">
    <!-- Statistiques -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 w-full">
        
        <!-- Total Contacts -->
        <div class="card p-6 bg-[#1a2a4a] text-white rounded-xl shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-200 text-sm">Total contacts</p>
                    <p class="text-3xl font-bold text-white">{{ $stats['total'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center text-white">
                    <i class="fas fa-users text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Favoris -->
        <div class="card p-6 bg-[#c45a2a] text-white rounded-xl shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm">Favoris</p>
                    <p class="text-3xl font-bold text-white">{{ $stats['favoris'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center text-white">
                    <i class="fas fa-star text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Avec téléphone -->
        <div class="card p-6 bg-white border border-gray-100 rounded-xl shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Avec téléphone</p>
                    <p class="text-3xl font-bold text-[#1a2a4a]">{{ $stats['avec_telephone'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-phone text-[#2c4a7a] text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Dans la corbeille -->
        <div class="card p-6 bg-white border border-gray-100 rounded-xl shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Dans la corbeille</p>
                    <p class="text-3xl font-bold text-red-600">{{ $stats['supprimes'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-trash text-red-500 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 w-full">
        <!-- Répartition par groupe -->
        <div class="card p-6 w-full bg-white rounded-xl shadow-sm border border-gray-100">
            <h3 class="font-bold text-[#1a2a4a] mb-4 flex items-center">
                <i class="fas fa-chart-pie text-[#c45a2a] mr-2"></i> Répartition par groupe
            </h3>
            <div class="space-y-3">
                @foreach($stats['par_groupe'] as $groupe => $count)
                    @php
                        $pourcentage = $stats['par_groupe_pourcentage'][$groupe] ?? 0;
                        $couleurs = [
                            'famille' => 'bg-[#2c4a7a]',
                            'amis' => 'bg-[#c45a2a]',
                            'Collègue' => 'bg-[#4a7aaa]',
                            'autres' => 'bg-gray-500'
                        ];
                        $icones = [
                            'famille' => 'fa-users',
                            'amis' => 'fa-user-friends',
                            'Collègue' => 'fa-briefcase',
                            'autres' => 'fa-ellipsis-h'
                        ];
                    @endphp
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="flex items-center">
                                <i class="fas {{ $icones[$groupe] ?? 'fa-circle' }} text-[#1a2a4a] mr-2"></i>
                                <span class="capitalize">{{ $groupe }}</span>
                            </span>
                            <span class="font-medium">{{ $count }} ({{ $pourcentage }}%)</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="{{ $couleurs[$groupe] ?? 'bg-gray-600' }} h-2.5 rounded-full transition-all duration-500" tyle="width: {{ $pourcentage }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Anniversaires -->
        <div class="card p-6 w-full bg-white rounded-xl shadow-sm border border-gray-100">
            <h3 class="font-bold text-[#1a2a4a] mb-4 flex items-center">
                <i class="fas fa-birthday-cake text-[#c45a2a] mr-2"></i> Anniversaires
            </h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-orange-50 rounded-xl border border-orange-200">
                    <div>
                        <p class="text-sm text-gray-600">Aujourd'hui</p>
                        <p class="text-2xl font-bold text-[#1a2a4a]">{{ $stats['anniversaires_aujourdhui'] ?? 0 }}</p>
                    </div>
                    <div class="w-10 h-10 bg-[#c45a2a] rounded-full flex items-center justify-center text-white">
                        <i class="fas fa-gift"></i>
                    </div>
                </div>
                <div class="flex items-center justify-between p-4 bg-blue-50 rounded-xl border border-blue-200">
                    <div>
                        <p class="text-sm text-gray-600">Ce mois-ci</p>
                        <p class="text-2xl font-bold text-[#1a2a4a]">{{ $stats['anniversaires_mois'] ?? 0 }}</p>
                    </div>
                    <div class="w-10 h-10 bg-[#1a2a4a] rounded-full flex items-center justify-center text-white">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
            </div>

            @if(isset($stats['anniversaires_aujourdhui']) && $stats['anniversaires_aujourdhui'] > 0)
                <div class="mt-4 p-3 bg-orange-100 border border-orange-300 rounded-lg">
                    <p class="text-orange-700 text-sm">
                        <i class="fas fa-gift mr-2"></i>
                        {{ $stats['anniversaires_aujourdhui'] }} contact(s) fêtent leur anniversaire aujourd'hui !
                    </p>
                </div>
            @endif
        </div>
    </div>

    <!-- Derniers contacts -->
    <div class="card p-6 w-full bg-white rounded-xl shadow-sm border border-gray-100">
        <h3 class="font-bold text-[#1a2a4a] mb-4 flex items-center">
            <i class="fas fa-clock text-[#c45a2a] mr-2"></i> Derniers contacts ajoutés
        </h3>
        @if(isset($recents) && $recents->count() > 0)
            <div class="overflow-x-auto w-full">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-[#1a2a4a] text-white">
                            <th class="text-left py-2 px-4 rounded-l-lg">Nom</th>
                            <th class="text-left py-2 px-4 hidden md:table-cell">Téléphone</th>
                            <th class="text-left py-2 px-4 hidden lg:table-cell">Groupe</th>
                            <th class="text-left py-2 px-4 rounded-r-lg">Ajouté le</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recents as $contact)
                            <tr class="border-b border-gray-100 hover:bg-orange-50 transition">
                                <td class="py-3 px-4">
                                    <a href="{{ route('contacts.show', $contact->id) }}" class="text-[#1a2a4a] hover:text-[#c45a2a] font-medium">
                                        {{ $contact->name }} {{ $contact->surname }}
                                    </a>
                                    @if($contact->favoris)
                                        <i class="fas fa-star text-[#c45a2a] ml-1 text-xs"></i>
                                    @endif
                                </td>
                                <td class="py-3 px-4 hidden md:table-cell text-gray-600">{{ $contact->phone ?? '-' }}</td>
                                <td class="py-3 px-4 hidden lg:table-cell">
                                    <span class="badge-{{ $contact->group }} px-3 py-1 rounded-full text-xs">
                                        {{ $contact->group }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-gray-500 text-sm">{{ $contact->created_at->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 text-center py-8">
                <i class="fas fa-inbox text-2xl block mb-2"></i>
                Aucun contact pour le moment. 
                <a href="{{ route('contacts.create') }}" class="text-[#c45a2a] hover:underline">Ajoutez votre premier contact !</a>
            </p>
        @endif
    </div>
</div>
@endsection