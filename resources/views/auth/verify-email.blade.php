@extends('layouts.app')

@section('title', 'Vérification d\'email')

@section('header-title', 'Vérification d\'email')
@section('header-subtitle', 'Activez votre compte pour accéder à toutes les fonctionnalités')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center">
    <div class="w-full max-w-md">
        <div class="card p-8 text-center">
            <div class="w-24 h-24 bg-orange-tres-clair rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-envelope text-5xl text-orange-fonce"></i>
            </div>
            
            <h2 class="text-2xl font-bold text-bleu-fonce mb-3">Vérifiez votre adresse email</h2>
            
            <div class="bg-blue-50 rounded-lg p-4 mb-6">
                <p class="text-bleu-fonce text-sm">
                    <i class="fas fa-info-circle text-orange-fonce mr-2"></i>
                    Un lien de vérification a été envoyé à 
                    <strong>{{ Auth::user()->email }}</strong>
                </p>
            </div>

            <p class="text-gray-600 text-sm mb-6">
                Cliquez sur le lien dans l'email pour activer votre compte.
                Si vous ne l'avez pas reçu, vous pouvez en demander un nouveau.
            </p>

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 rounded-lg mb-4 text-sm">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
            @endif

            <div class="space-y-3">
                <form method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <button type="submit" class="btn-primary w-full py-3">
                        <i class="fas fa-redo mr-2"></i> Renvoyer l'email de vérification
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-outline-primary w-full py-3">
                        <i class="fas fa-sign-out-alt mr-2"></i> Se déconnecter
                    </button>
                </form>
            </div>

            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <p class="text-xs text-gray-500">
                    <i class="fas fa-lightbulb text-orange-fonce mr-2"></i>
                    Astuce : Vérifiez vos spams si vous ne trouvez pas l'email.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection