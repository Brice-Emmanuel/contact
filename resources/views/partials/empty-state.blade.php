@props([
    'icon' => 'fa-address-book',
    'title' => 'Aucun élément trouvé',
    'message' => 'Commencez à ajouter des éléments !',
    'actionText' => null,
    'actionUrl' => null
])

<div class="card p-12 text-center">
    <div class="text-6xl text-gray-300 mb-4">
        <i class="fas {{ $icon }}"></i>
    </div>
    <h3 class="text-xl font-bold text-bleu-fonce mb-2">{{ $title }}</h3>
    <p class="text-gray-500 mb-4">{{ $message }}</p>
    @if($actionText && $actionUrl)
        <a href="{{ $actionUrl }}" class="btn-orange inline-block">
            <i class="fas fa-plus mr-2"></i> {{ $actionText }}
        </a>
    @endif
    {{ $slot }}
</div>