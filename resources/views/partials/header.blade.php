<header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-30">
    <div class="px-4 md:px-6 py-3 flex items-center justify-between">
        
        <div class="flex items-center gap-2">
            <!-- Bouton toggle desktop (3 traits) -->
            <button id="toggleSidebarBtn" 
                    onclick="toggleSidebar()" 
                    class="hidden md:flex text-bleu-fonce hover:text-orange-fonce transition text-xl p-2 hover:bg-gray-100 rounded-lg items-center justify-center"
                    title="Menu">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Bouton toggle mobile (3 traits) -->
            <button id="toggleSidebarMobileBtn" 
                    onclick="toggleSidebarMobile()" 
                    class="md:hidden text-bleu-fonce text-xl p-2 hover:bg-gray-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-bars"></i>
            </button>

            <div>
                <h2 class="text-bleu-fonce font-semibold text-lg">
                    @yield('header-title', 'Gestion des contacts')
                </h2>
                <p class="text-gray-500 text-sm hidden sm:block">
                    @yield('header-subtitle', 'Bienvenue sur votre carnet de contacts')
                </p>
            </div>
        </div>

        <div class="flex items-center space-x-4">
            <!-- Recherche -->
            @if(request()->routeIs('contacts.*') && !request()->routeIs('contacts.create') && !request()->routeIs('contacts.edit'))
                <form action="{{ route('contacts.index') }}" method="GET" class="hidden md:flex items-center">
                    <div class="relative">
                        <input type="text" 
                               name="recherche" 
                               placeholder="Rechercher..." 
                               value="{{ request('recherche') }}"
                               class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-bleu-fonce focus:ring-2 focus:ring-bleu-fonce/20 w-48 lg:w-64">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>
                    <button type="submit" class="ml-2 px-4 py-2 bg-bleu-fonce text-white rounded-lg text-sm hover:bg-bleu-moyen transition">
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </form>
            @endif

            <!-- Infos utilisateur -->
            <div class="flex items-center space-x-3">
                <div class="hidden sm:block text-right">
                    <p class="text-sm font-medium text-bleu-fonce">{{ Auth::user()->name ?? 'Utilisateur' }}</p>
                    <p class="text-xs text-gray-500">{{ Auth::user()->email ?? '' }}</p>
                </div>
                <div class="w-9 h-9 bg-orange-fonce rounded-full flex items-center justify-center text-white font-bold">
                    {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                </div>
            </div>
        </div>
    </div>
</header>