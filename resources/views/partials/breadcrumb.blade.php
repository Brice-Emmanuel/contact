@props(['items' => []])

<nav class="flex items-center text-sm text-gray-500 mb-4" aria-label="Breadcrumb">
    <ol class="flex items-center space-x-2">
        <li>
            <a href="{{ route('dashboard') }}" class="hover:text-bleu-fonce transition">
                <i class="fas fa-home"></i>
            </a>
        </li>
        
        @foreach($items as $item)
            <li class="flex items-center">
                <i class="fas fa-chevron-right text-gray-300 mx-2 text-xs"></i>
                @if(isset($item['url']))
                    <a href="{{ $item['url'] }}" class="hover:text-bleu-fonce transition">
                        {{ $item['label'] }}
                    </a>
                @else
                    <span class="text-bleu-fonce font-medium">{{ $item['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>