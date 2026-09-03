@extends('layouts.app')

@section('title', 'Connexion')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-bleu-fonce rounded-2xl flex items-center justify-center mx-auto shadow-lg">
                <i class="fas fa-address-book text-white text-3xl"></i>
            </div>
            <h2 class="mt-4 text-2xl font-bold text-bleu-fonce">Bienvenue</h2>
            <p class="text-gray-500">Connectez-vous à votre carnet de contacts</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 rounded-lg mb-4">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif

        <div class="card p-8">
            <form method="POST" action="{{ route('login') }}" id="login-form">
                @csrf

                <div class="mb-5">
                    <label for="email_username" class="form-label">
                        <i class="fas fa-envelope text-orange-fonce mr-2"></i> Adresse email
                    </label>

                    <!-- Champ préfixe + Sélecteur de domaine -->
                    <div class="flex rounded-lg border border-gray-300 focus-within:border-orange-fonce overflow-hidden">
                        <input type="text" id="email_username"
                               class="w-full px-3 py-2 outline-none text-gray-700"
                               placeholder="votre.nom" required autofocus>
                        <select id="email_domain" class="bg-gray-100 text-gray-600 px-2 py-2 font-medium outline-none border-l border-gray-200 text-sm cursor-pointer">
                            <option value="@gmail.com">@gmail.com</option>
                            <option value="@icloud.com">@icloud.com</option>
                        </select>
                    </div>

                    <!-- Champ caché transmis à Laravel -->
                    <input type="hidden" name="email" id="email_full" value="{{ old('email') }}">

                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock text-orange-fonce mr-2"></i> Mot de passe
                    </label>
                    <input type="password" name="password" id="password"
                           class="form-input @error('password') border-red-500 @enderror"
                           placeholder="••••••••" required>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between mb-5">
                    <label class="flex items-center text-sm text-gray-600 cursor-pointer">
                        <input type="checkbox" name="remember" id="remember"
                               class="w-4 h-4 text-orange-fonce rounded border-gray-300 focus:ring-orange-fonce">
                        <span class="ml-2">Se souvenir de moi</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="text-sm text-orange-fonce hover:text-orange-moyen transition">
                        Mot de passe oublié ?
                    </a>
                </div>

                <button type="submit" class="btn-primary w-full py-3 text-lg font-semibold">
                    <i class="fas fa-sign-in-alt mr-2"></i> Se connecter
                </button>

                <p class="text-center text-gray-500 text-sm mt-4">
                    Pas encore de compte ? 
                    <a href="{{ route('register') }}" class="text-orange-fonce font-medium hover:text-orange-moyen transition">
                        S'inscrire
                    </a>
                </p>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const loginForm = document.getElementById('login-form');
    const usernameInput = document.getElementById('email_username');
    const domainSelect = document.getElementById('email_domain');
    const emailFullInput = document.getElementById('email_full');

    // Restaurer la valeur si retour arrière après erreur
    if (emailFullInput.value) {
        if (emailFullInput.value.includes('@icloud.com')) {
            domainSelect.value = '@icloud.com';
            usernameInput.value = emailFullInput.value.replace('@icloud.com', '');
        } else if (emailFullInput.value.includes('@gmail.com')) {
            domainSelect.value = '@gmail.com';
            usernameInput.value = emailFullInput.value.replace('@gmail.com', '');
        }
    }

    function updateFullEmail() {
        let cleanVal = usernameInput.value.replace(/@.*$/, '').trim();
        usernameInput.value = cleanVal;
        emailFullInput.value = cleanVal ? cleanVal + domainSelect.value : '';
    }

    usernameInput.addEventListener('input', updateFullEmail);
    domainSelect.addEventListener('change', updateFullEmail);

    loginForm.addEventListener('submit', function () {
        updateFullEmail();
    });
});
</script>
@endsection