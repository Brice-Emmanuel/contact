<!-- ===== SIDEBAR DESKTOP & TABLETTE ===== -->
<aside class="sidebar-desktop hidden md:flex md:flex-col w-64 bg-bleu-fonce h-screen fixed left-0 top-0 z-40 transition-all duration-300 border-r border-blue-900">
    <div class="p-4 border-b border-blue-800 flex items-center justify-between">
        <div class="flex items-center">
            <div class="w-10 h-10 bg-orange-fonce rounded-lg flex items-center justify-center mr-3 shrink-0">
                <i class="fas fa-address-book text-white text-xl"></i>
            </div>
            <div>
                <h1 class="text-white font-bold text-lg leading-tight">Contacts</h1>
                <p class="text-blue-300 text-xs">Gestion de carnet</p>
            </div>
        </div>
        <button onclick="toggleSidebar()" class="text-blue-300 hover:text-white transition md:hidden">
            <i class="fas fa-times"></i>
        </button>
    </div>

    @php
        $user = Auth::user();
        $totalContacts = $user ? \App\Models\Contact::where('user_id', $user->id)->count() : 0;
        $favorisCount = $user ? \App\Models\Contact::where('user_id', $user->id)->where('favoris', true)->count() : 0;
        $trashedCount = $user ? \App\Models\Contact::where('user_id', $user->id)->onlyTrashed()->count() : 0;
    @endphp

    <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
        <a href="{{ route('dashboard') }}" class="sidebar-link flex items-center justify-between p-2.5 rounded-lg text-white hover:bg-blue-800/50 transition {{ request()->routeIs('dashboard') ? 'bg-blue-800 font-semibold' : '' }}">
            <div class="flex items-center space-x-3">
                <i class="fas fa-chart-pie w-5 text-center text-blue-300"></i>
                <span>Tableau de bord</span>
            </div>
        </a>

        <a href="{{ route('contacts.index') }}" class="sidebar-link flex items-center justify-between p-2.5 rounded-lg text-white hover:bg-blue-800/50 transition {{ request()->routeIs('contacts.index') ? 'bg-blue-800 font-semibold' : '' }}">
            <div class="flex items-center space-x-3">
                <i class="fas fa-address-card w-5 text-center text-blue-300"></i>
                <span>Tous les contacts</span>
            </div>
            @if($totalContacts > 0)
                <span class="bg-orange-fonce text-white text-xs font-semibold px-2.5 py-0.5 rounded-full min-w-[20px] text-center">{{ $totalContacts }}</span>
            @endif
        </a>

        <a href="{{ route('contacts.favoris') }}" class="sidebar-link flex items-center justify-between p-2.5 rounded-lg text-white hover:bg-blue-800/50 transition {{ request()->routeIs('contacts.favoris') ? 'bg-blue-800 font-semibold' : '' }}">
            <div class="flex items-center space-x-3">
                <i class="fas fa-star w-5 text-center text-orange-clair"></i>
                <span>Favoris</span>
            </div>
            @if($favorisCount > 0)
                <span class="bg-orange-fonce text-white text-xs font-semibold px-2.5 py-0.5 rounded-full min-w-[20px] text-center">{{ $favorisCount }}</span>
            @endif
        </a>

        <a href="{{ route('contacts.trashed') }}" class="sidebar-link flex items-center justify-between p-2.5 rounded-lg text-white hover:bg-blue-800/50 transition {{ request()->routeIs('contacts.trashed') ? 'bg-blue-800 font-semibold' : '' }}">
            <div class="flex items-center space-x-3">
                <i class="fas fa-trash w-5 text-center text-blue-300"></i>
                <span>Corbeille</span>
            </div>
            @if($trashedCount > 0)
                <span class="bg-red-600 text-white text-xs font-semibold px-2.5 py-0.5 rounded-full min-w-[20px] text-center">{{ $trashedCount }}</span>
            @endif
        </a>

        <hr class="border-blue-800/60 my-4">

        <a href="{{ route('contacts.create') }}" class="sidebar-link flex items-center space-x-3 p-2.5 rounded-lg text-white bg-orange-fonce hover:bg-orange-moyen transition">
            <i class="fas fa-plus-circle w-5 text-center"></i>
            <span>Nouveau contact</span>
        </a>

        <a href="{{ route('contacts.export') }}" class="sidebar-link flex items-center space-x-3 p-2.5 rounded-lg text-white hover:bg-blue-800/50 transition">
            <i class="fas fa-file-export w-5 text-center text-blue-300"></i>
            <span>Exporter CSV</span>
        </a>
    </nav>

    <div class="p-4 border-t border-blue-800">
        <div class="flex items-center justify-between">
            <div class="flex items-center min-w-0">
                <div class="w-8 h-8 bg-orange-clair rounded-full flex items-center justify-center shrink-0 font-bold text-bleu-fonce text-sm">
                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                </div>
                <div class="ml-3 min-w-0">
                    <p class="text-white text-sm font-medium truncate">{{ Auth::user()->name ?? 'Utilisateur' }}</p>
                    <p class="text-blue-300 text-xs truncate">{{ Auth::user()->email ?? '' }}</p>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="inline ml-2 shrink-0">
                @csrf
                <button type="submit" class="text-blue-300 hover:text-white transition p-1" title="Déconnexion">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </div>
</aside>

<!-- ===== SIDEBAR MOBILE ===== -->
<div id="sidebarOverlay" class="sidebar-overlay fixed inset-0 bg-black/50 z-40 hidden md:hidden" onclick="closeSidebarMobile()"></div>

<div id="sidebarMobile" class="sidebar-mobile md:hidden bg-bleu-fonce fixed inset-y-0 left-0 w-64 z-50 flex flex-col justify-between transform -translate-x-full transition-transform duration-300">
    <div>
        <div class="p-4 border-b border-blue-800 flex justify-between items-center">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-orange-fonce rounded-lg flex items-center justify-center mr-3 shrink-0">
                    <i class="fas fa-address-book text-white text-xl"></i>
                </div>
                <h1 class="text-white font-bold text-lg">Contacts</h1>
            </div>
            <button onclick="closeSidebarMobile()" class="text-white text-xl hover:text-orange-clair transition">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <nav class="p-4 space-y-1 overflow-y-auto">
            <a href="{{ route('dashboard') }}" class="sidebar-link flex items-center justify-between p-2.5 rounded-lg text-white hover:bg-blue-800/50 transition {{ request()->routeIs('dashboard') ? 'bg-blue-800 font-semibold' : '' }}">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-chart-pie w-5 text-center text-blue-300"></i>
                    <span>Tableau de bord</span>
                </div>
            </a>

            <a href="{{ route('contacts.index') }}" class="sidebar-link flex items-center justify-between p-2.5 rounded-lg text-white hover:bg-blue-800/50 transition {{ request()->routeIs('contacts.index') ? 'bg-blue-800 font-semibold' : '' }}">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-address-card w-5 text-center text-blue-300"></i>
                    <span>Tous les contacts</span>
                </div>
                @if($totalContacts > 0)
                    <span class="bg-orange-fonce text-white text-xs font-semibold px-2.5 py-0.5 rounded-full min-w-[20px] text-center">{{ $totalContacts }}</span>
                @endif
            </a>

            <a href="{{ route('contacts.favoris') }}" class="sidebar-link flex items-center justify-between p-2.5 rounded-lg text-white hover:bg-blue-800/50 transition {{ request()->routeIs('contacts.favoris') ? 'bg-blue-800 font-semibold' : '' }}">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-star w-5 text-center text-orange-clair"></i>
                    <span>Favoris</span>
                </div>
                @if($favorisCount > 0)
                    <span class="bg-orange-fonce text-white text-xs font-semibold px-2.5 py-0.5 rounded-full min-w-[20px] text-center">{{ $favorisCount }}</span>
                @endif
            </a>

            <a href="{{ route('contacts.trashed') }}" class="sidebar-link flex items-center justify-between p-2.5 rounded-lg text-white hover:bg-blue-800/50 transition {{ request()->routeIs('contacts.trashed') ? 'bg-blue-800 font-semibold' : '' }}">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-trash w-5 text-center text-blue-300"></i>
                    <span>Corbeille</span>
                </div>
                @if($trashedCount > 0)
                    <span class="bg-red-600 text-white text-xs font-semibold px-2.5 py-0.5 rounded-full min-w-[20px] text-center">{{ $trashedCount }}</span>
                @endif
            </a>

            <hr class="border-blue-800/60 my-4">

            <a href="{{ route('contacts.create') }}" class="sidebar-link flex items-center space-x-3 p-2.5 rounded-lg text-white bg-orange-fonce">
                <i class="fas fa-plus-circle w-5 text-center"></i>
                <span>Nouveau contact</span>
            </a>

            <a href="{{ route('contacts.export') }}" class="sidebar-link flex items-center space-x-3 p-2.5 rounded-lg text-white hover:bg-blue-800/50 transition">
                <i class="fas fa-file-export w-5 text-center text-blue-300"></i>
                <span>Exporter CSV</span>
            </a>
        </nav>
    </div>

    <div class="p-4 border-t border-blue-800">
        <div class="flex items-center justify-between">
            <div class="flex items-center min-w-0">
                <div class="w-8 h-8 bg-orange-clair rounded-full flex items-center justify-center shrink-0 font-bold text-bleu-fonce text-sm">
                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                </div>
                <div class="ml-3 min-w-0">
                    <p class="text-white text-sm font-medium truncate">{{ Auth::user()->name ?? 'Utilisateur' }}</p>
                    <p class="text-blue-300 text-xs truncate">{{ Auth::user()->email ?? '' }}</p>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="inline ml-2 shrink-0">
                @csrf
                <button type="submit" class="text-blue-300 hover:text-white transition">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </div>
</div>