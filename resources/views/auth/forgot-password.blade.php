@extends('layouts.app')

@section('title', 'Mot de passe oublié')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center">
    <div class="w-full max-w-md">
        <div class="card p-8">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-orange-fonce rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-lock text-white text-2xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-bleu-fonce">Mot de passe oublié</h2>
                <p class="text-gray-500 text-sm mt-1">
                    Entrez votre email pour recevoir un lien de réinitialisation
                </p>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 rounded-lg mb-4 text-sm">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="mb-5">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope text-orange-fonce mr-2"></i> Adresse email
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                           class="form-input @error('email') border-red-500 @enderror"
                           placeholder="votre@email.com" required>
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn-orange w-full py-3">
                    <i class="fas fa-paper-plane mr-2"></i> Envoyer le lien de réinitialisation
                </button>

                <p class="text-center text-sm text-gray-500 mt-4">
                    <a href="{{ route('login') }}" class="text-bleu-fonce hover:text-bleu-clair transition">
                        <i class="fas fa-arrow-left mr-1"></i> Retour à la connexion
                    </a>
                </p>
            </form>
        </div>
    </div>
</div>
@endsection