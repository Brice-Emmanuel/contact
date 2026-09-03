@props([
    'route' => 'contacts.index',
    'showGroup' => true,
    'showFavoris' => true
])

<div class="card p-4">
    <form method="GET" action="{{ route($route) }}" class="flex flex-wrap items-center gap-3">
        <!-- Recherche -->
        <div class="flex-1 min-w-[200px]">
            <div class="relative">
                <input type="text" 
                       name="recherche" 
                       placeholder="Rechercher un contact..." 
                       value="{{ request('recherche') }}"
                       class="form-input pl-10">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>

        @if($showGroup)
            <!-- Filtre groupe -->
            <div class="w-full sm:w-auto">
                <select name="groupe" class="form-input bg-white">
                    <option value="">Tous les groupes</option>
                    <option value="famille" {{ request('groupe') == 'famille' ? 'selected' : '' }}> Famille</option>
                    <option value="amis" {{ request('groupe') == 'amis' ? 'selected' : '' }}> Amis</option>
                    <option value="Collègue" {{ request('groupe') == 'Collègue' ? 'selected' : '' }}> Collègue</option>
                    <option value="autres" {{ request('groupe') == 'autres' ? 'selected' : '' }}> Autres</option>
                </select>
            </div>
        @endif

        @if($showFavoris)
            <!-- Filtre favoris -->
            <div class="flex items-center gap-2">
                <input type="checkbox" 
                       name="favoris" 
                       value="true" 
                       id="favoris"
                       {{ request('favoris') == 'true' ? 'checked' : '' }}
                       class="w-4 h-4 text-orange-fonce rounded border-gray-300 focus:ring-orange-fonce">
                <label for="favoris" class="text-sm text-bleu-fonce font-medium cursor-pointer">
                    <i class="fas fa-star text-orange-fonce"></i> Favoris uniquement
                </label>
            </div>
        @endif

        <button type="submit" class="btn-primary px-6">
            <i class="fas fa-filter"></i> Filtrer
        </button>

        @if(request()->hasAny(['recherche', 'groupe', 'favoris']))
            <a href="{{ route($route) }}" class="text-sm text-gray-500 hover:text-orange-fonce">
                <i class="fas fa-times"></i> Réinitialiser
            </a>
        @endif
    </form>
</div