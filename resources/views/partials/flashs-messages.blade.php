@if(session('success'))
    <div class="mb-4 p-4 rounded-lg bg-green-100 border border-green-200 text-green-800 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <i class="fas fa-check-circle text-green-600"></i>
            <span>{{ session('success') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800">&times;</button>
    </div>
@endif

@if(session('error'))
    <div class="mb-4 p-4 rounded-lg bg-red-100 border border-red-200 text-red-800 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <i class="fas fa-exclamation-circle text-red-600"></i>
            <span>{{ session('error') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800">&times;</button>
    </div>
@endif

{{-- BANNIÈRE DE NOTIFICATION POUR LES ANNIVERSAIRES DES CONTACTS --}}
@auth
    @php
        $today = \Carbon\Carbon::today();
        $birthdayContacts = auth()->user()->contacts()
            ->whereNotNull('Birthday')
            ->whereMonth('Birthday', $today->month)
            ->whereDay('Birthday', $today->day)
            ->get();
    @endphp

    @if($birthdayContacts->isNotEmpty())
        <div class="mb-6 p-4 rounded-xl bg-orange-100 border border-orange-300 text-orange-900 shadow-md flex items-center justify-between animate-pulse">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-orange-fonce text-white rounded-full">
                    <i class="fas fa-birthday-cake text-xl"></i>
                </div>
                <div>
                    <h4 class="font-bold text-base">🎉 Anniversaire(s) à souhaiter aujourd'hui !</h4>
                    <p class="text-sm text-gray-700">
                        @foreach($birthdayContacts as $contact)
                            <span class="font-semibold">{{ $contact->name }} {{ $contact->surname }}</span>
                            @if(!$loop->last), @endif
                        @endforeach
                        fête(nt) leur anniversaire aujourd'hui !
                    </p>
                </div>
            </div>
            <a href="{{ route('contacts.anniversaires.aujourdhui') }}" class="btn-orange text-xs px-4 py-2 rounded-lg font-bold">
                <i class="fas fa-eye mr-1"></i> Voir
            </a>
        </div>
    @endif
@endauth