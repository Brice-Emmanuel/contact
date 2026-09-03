@extends('layouts.app')

@section('title', 'Réinitialisation du mot de passe')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center">
    <div class="w-full max-w-md">
        <div class="card p-8">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-bleu-fonce rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-key text-white text-2xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-bleu-fonce">Nouveau mot de passe</h2>
                <p class="text-gray-500 text-sm mt-1">
                    Choisissez un nouveau mot de passe sécurisé
                </p>
            </div>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="mb-4">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope text-orange-fonce mr-2"></i> Adresse email
                    </label>
                    <input type="email" name="email" id="email" value="{{ $email ?? old('email') }}"
                           class="form-input @error('email') border-red-500 @enderror" required>
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock text-orange-fonce mr-2"></i> Nouveau mot de passe
                    </label>
                    <input type="password" name="password" id="password"
                           class="form-input @error('password') border-red-500 @enderror"
                           placeholder="•••••••• (min. 8 caractères)" required>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="password_confirmation" class="form-label">
                        <i class="fas fa-check-circle text-orange-fonce mr-2"></i> Confirmer le mot de passe
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="form-input" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn-primary w-full py-3">
                    <i class="fas fa-save mr-2"></i> Réinitialiser le mot de passe
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