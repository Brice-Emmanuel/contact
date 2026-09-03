@props(['stats'])

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Total contacts -->
    <div class="card p-6 bg-gradient-to-br from-bleu-fonce to-bleu-moyen text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-200 text-sm">Total contacts</p>
                <p class="text-3xl font-bold">{{ $stats['total'] ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                <i class="fas fa-users text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Favoris -->
    <div class="card p-6 bg-gradient-to-br from-orange-fonce to-orange-moyen text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-orange-100 text-sm">Favoris</p>
                <p class="text-3xl font-bold">{{ $stats['favoris'] ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                <i class="fas fa-star text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Avec téléphone -->
    <div class="card p-6 bg-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Avec téléphone</p>
                <p class="text-3xl font-bold text-bleu-fonce">{{ $stats['avec_telephone'] ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 bg-bleu-tres-clair rounded-xl flex items-center justify-center">
                <i class="fas fa-phone text-bleu-moyen text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Corbeille -->
    <div class="card p-6 bg-white">
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