 @extends('layouts.app')

@section('title', 'Inscription')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-orange-fonce rounded-2xl flex items-center justify-center mx-auto shadow-lg">
                <i class="fas fa-user-plus text-white text-3xl"></i>
            </div>
            <h2 class="mt-4 text-2xl font-bold text-bleu-fonce">Créer un compte</h2>
            <p class="text-gray-500">Rejoignez la communauté de gestion de contacts</p>
        </div>

        <div class="card p-8">
            <form method="POST" action="{{ route('register') }}" id="register-form">
                @csrf

                <div class="mb-4">
                    <label for="name" class="form-label">
                        <i class="fas fa-user text-orange-fonce mr-2"></i> Nom complet
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                           class="form-input @error('name') border-red-500 @enderror"
                           placeholder="Jean Dupont" required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <div class="flex items-center justify-between mb-1">
                        <label for="email_username" class="form-label mb-0">
                            <i class="fas fa-envelope text-orange-fonce mr-2"></i> Adresse email
                        </label>
                        <span id="email-valid-badge" class="hidden text-green-600 font-semibold text-xs flex items-center">
                            <i class="fas fa-check-circle mr-1"></i> Valide
                        </span>
                    </div>

                    <!-- Champ préfixe + Sélecteur de domaine -->
                    <div class="flex rounded-lg border border-gray-300 focus-within:border-orange-fonce overflow-hidden">
                        <input type="text" id="email_username"
                               class="w-full px-3 py-2 outline-none text-gray-700"
                               placeholder="votre.nom" required>
                        <select id="email_domain" class="bg-gray-100 text-gray-600 px-2 py-2 font-medium outline-none border-l border-gray-200 text-sm cursor-pointer">
                            <option value="@gmail.com">@gmail.com</option>
                            <option value="@icloud.com">@icloud.com</option>
                        </select>
                    </div>

                    <!-- Champ masqué transmis à Laravel -->
                    <input type="hidden" name="email" id="email_full" value="{{ old('email') }}">

                    <p id="email-error" class="hidden text-red-500 text-xs mt-1">
                        Seuls les lettres, chiffres, points, tirets et underscores sont autorisés.
                    </p>
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <div class="flex items-center justify-between">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock text-orange-fonce mr-2"></i> Mot de passe
                        </label>
                        <span id="password-valid-badge" class="hidden text-green-600 font-semibold text-xs flex items-center">
                            <i class="fas fa-check-circle mr-1"></i> Mot de passe sécurisé
                        </span>
                    </div>

                    <input type="password" name="password" id="password"
                           class="form-input @error('password') border-red-500 @enderror"
                           placeholder="••••••••" required>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror

                    <!-- Indicator des règles -->
                    <div id="password-rules" class="hidden mt-3 p-3 bg-gray-50 rounded-lg border border-gray-100 text-xs transition-all">
                        <p class="font-medium text-gray-600 mb-2">Critères restants à valider :</p>
                        <ul class="space-y-1">
                            <li id="rule-length" class="text-red-500 flex items-center">
                                <i class="fas fa-times-circle mr-2"></i> Au moins 8 caractères
                            </li>
                            <li id="rule-case" class="text-red-500 flex items-center">
                                <i class="fas fa-times-circle mr-2"></i> Une majuscule et une minuscule
                            </li>
                            <li id="rule-number" class="text-red-500 flex items-center">
                                <i class="fas fa-times-circle mr-2"></i> Au moins un chiffre
                            </li>
                            <li id="rule-symbol" class="text-red-500 flex items-center">
                                <i class="fas fa-times-circle mr-2"></i> Au moins un symbole (@, #, $, !, etc.)
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="mb-5">
                    <div class="flex items-center justify-between">
                        <label for="password_confirmation" class="form-label">
                            <i class="fas fa-check-circle text-orange-fonce mr-2"></i> Confirmer le mot de passe
                        </label>
                        <span id="confirm-valid-badge" class="hidden text-green-600 font-semibold text-xs flex items-center">
                            <i class="fas fa-check-circle mr-1"></i> Identiques
                        </span>
                    </div>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="form-input" placeholder="••••••••" required>
                    <p id="confirm-error" class="hidden text-red-500 text-xs mt-1">
                        Les mots de passe ne correspondent pas.
                    </p>
                </div>

                <button type="submit" id="submit-btn" class="btn-orange w-full py-3 text-lg font-semibold opacity-50 cursor-not-allowed" disabled>
                    <i class="fas fa-user-plus mr-2"></i> S'inscrire
                </button>

                <p class="text-center text-gray-500 text-sm mt-4">
                    Déjà un compte ? 
                    <a href="{{ route('login') }}" class="text-bleu-fonce font-medium hover:text-bleu-clair transition">
                        Se connecter
                    </a>
                </p>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const nameInput = document.getElementById('name');
    const usernameInput = document.getElementById('email_username');
    const domainSelect = document.getElementById('email_domain');
    const emailFullInput = document.getElementById('email_full');
    const emailBadge = document.getElementById('email-valid-badge');
    const emailError = document.getElementById('email-error');
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    const rulesContainer = document.getElementById('password-rules');
    const passwordBadge = document.getElementById('password-valid-badge');
    const confirmBadge = document.getElementById('confirm-valid-badge');
    const confirmError = document.getElementById('confirm-error');
    const submitBtn = document.getElementById('submit-btn');

    const usernameRegex = /^[a-zA-Z0-9._-]+$/;

    const rules = {
        length: { regex: /.{8,}/, element: document.getElementById('rule-length') },
        case: { regex: /(?=.*[a-z])(?=.*[A-Z])/, element: document.getElementById('rule-case') },
        number: { regex: /(?=.*\d)/, element: document.getElementById('rule-number') },
        symbol: { regex: /(?=.*[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?])/, element: document.getElementById('rule-symbol') }
    };

    if (emailFullInput.value) {
        if (emailFullInput.value.includes('@icloud.com')) {
            domainSelect.value = '@icloud.com';
            usernameInput.value = emailFullInput.value.replace('@icloud.com', '');
        } else if (emailFullInput.value.includes('@gmail.com')) {
            domainSelect.value = '@gmail.com';
            usernameInput.value = emailFullInput.value.replace('@gmail.com', '');
        }
    }

    function validateEmail() {
        let cleanVal = usernameInput.value.replace(/@.*$/, '').trim();
        usernameInput.value = cleanVal;

        if (cleanVal.length === 0) {
            emailBadge.classList.add('hidden');
            emailError.classList.add('hidden');
            emailFullInput.value = '';
            return false;
        }

        if (usernameRegex.test(cleanVal)) {
            emailBadge.classList.remove('hidden');
            emailError.classList.add('hidden');
            emailFullInput.value = cleanVal + domainSelect.value;
            return true;
        } else {
            emailBadge.classList.add('hidden');
            emailError.classList.remove('hidden');
            emailFullInput.value = '';
            return false;
        }
    }

    function validatePassword() {
        const val = passwordInput.value;
        let validCount = 0;
        const totalRules = Object.keys(rules).length;

        if (val.length === 0) {
            rulesContainer.classList.add('hidden');
            passwordBadge.classList.add('hidden');
            return false;
        }

        for (const key in rules) {
            const rule = rules[key];
            const isValid = rule.regex.test(val);

            if (isValid) {
                rule.element.classList.add('hidden');
                validCount++;
            } else {
                rule.element.classList.remove('hidden');
            }
        }

        const isPasswordFullyValid = (validCount === totalRules);

        if (isPasswordFullyValid) {
            rulesContainer.classList.add('hidden');
            passwordBadge.classList.remove('hidden');
        } else {
            rulesContainer.classList.remove('hidden');
            passwordBadge.classList.add('hidden');
        }

        return isPasswordFullyValid;
    }

    function validateConfirmation() {
        const passVal = passwordInput.value;
        const confirmVal = confirmInput.value;

        if (confirmVal.length === 0) {
            confirmBadge.classList.add('hidden');
            confirmError.classList.add('hidden');
            return false;
        }

        if (passVal === confirmVal) {
            confirmBadge.classList.remove('hidden');
            confirmError.classList.add('hidden');
            return true;
        } else {
            confirmBadge.classList.add('hidden');
            confirmError.classList.remove('hidden');
            return false;
        }
    }

    function checkForm() {
        const isNameValid = nameInput.value.trim().length > 0;
        const isEmailValid = validateEmail();
        const isPassValid = validatePassword();
        const isConfirmValid = validateConfirmation();

        if (isNameValid && isEmailValid && isPassValid && isConfirmValid) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }

    nameInput.addEventListener('input', checkForm);
    usernameInput.addEventListener('input', checkForm);
    domainSelect.addEventListener('change', checkForm);
    passwordInput.addEventListener('input', checkForm);
    confirmInput.addEventListener('input', checkForm);
});
</script>
@endsection