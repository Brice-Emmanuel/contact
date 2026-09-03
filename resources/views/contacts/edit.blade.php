@extends('layouts.app')

@section('title', 'Modifier ' . $contact->name . ' ' . $contact->surname)

@section('header-title', 'Modifier le contact')
@section('header-subtitle', $contact->name . ' ' . $contact->surname)

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="card p-6 md:p-8">
        <form method="POST" action="{{ route('contacts.update', $contact->id) }}" id="contact-form">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- NOM -->
                <div>
                    <label for="name" class="form-label">
                        <i class="fas fa-user text-orange-fonce mr-1"></i> <span class="text-red-500">*</span> Nom
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $contact->name) }}"
                           class="form-input @error('name') border-red-500 @enderror" required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- PRÉNOM -->
                <div>
                    <label for="surname" class="form-label">
                        <i class="fas fa-user-tag text-orange-fonce mr-1"></i> Prénom
                    </label>
                    <input type="text" name="surname" id="surname" value="{{ old('surname', $contact->surname) }}"
                           class="form-input @error('surname') border-red-500 @enderror">
                    @error('surname')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- TÉLÉPHONE AVEC INDICATIF PAYS -->
                <div>
                    <label for="phone" class="form-label">
                        <i class="fas fa-phone text-orange-fonce mr-1"></i> Téléphone
                    </label>
                    
                    <div class="relative">
                        <!-- Sélecteur personnalisé -->
                        <div class="flex rounded-lg border border-gray-300 focus-within:border-bleu-fonce focus-within:ring-2 focus-within:ring-bleu-fonce/20 overflow-hidden bg-white">
                            <button type="button" id="country-dropdown-btn" class="flex items-center pl-3 pr-2 bg-gray-50 border-r border-gray-200 gap-2 hover:bg-gray-100 transition-colors">
                                <span id="flag-icon" class="w-5 h-4 flex-shrink-0 flex items-center justify-center"></span>
                                <span id="selected-code" class="text-gray-800 text-sm font-semibold">+237</span>
                                <i class="fas fa-chevron-down text-xs text-gray-500 ml-1"></i>
                            </button>
                            <input type="tel" id="phone_input"
                                   class="w-full px-3 py-2 text-sm text-gray-800 outline-none">
                        </div>

                        <!-- Menu déroulant avec recherche -->
                        <div id="country-dropdown-menu" class="hidden absolute z-50 left-0 top-full mt-1 w-72 bg-white border border-gray-200 rounded-lg shadow-lg overflow-hidden">
                            <div class="p-2 border-b border-gray-100 bg-gray-50">
                                <div class="relative">
                                    <i class="fas fa-search absolute left-3 top-2.5 text-xs text-gray-400"></i>
                                    <input type="text" id="country-search" class="w-full pl-8 pr-3 py-1.5 text-xs border border-gray-300 rounded-md outline-none focus:border-bleu-fonce" placeholder="Rechercher un pays ou un code...">
                                </div>
                            </div>
                            <ul id="country-list" class="max-h-56 overflow-y-auto divide-y divide-gray-50">
                                <!-- Généré via JS -->
                            </ul>
                        </div>
                    </div>

                    <!-- Champ masqué envoyé au serveur -->
                    <input type="hidden" name="phone" id="phone_full" value="{{ old('phone', $contact->phone) }}">

                    <div class="flex justify-between items-center mt-1">
                        <p id="phone-hint" class="text-xs text-gray-500"></p>
                        <p id="phone-error" class="hidden text-red-500 text-xs font-medium"></p>
                    </div>

                    @error('phone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- EMAIL -->
                <div>
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope text-orange-fonce mr-1"></i> Email
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email', $contact->email) }}"
                           class="form-input @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- ADRESSE -->
            <div class="mt-4">
                <label for="adress" class="form-label">
                    <i class="fas fa-map-marker-alt text-orange-fonce mr-1"></i> Adresse
                </label>
                <textarea name="adress" id="adress" rows="2"
                          class="form-input @error('adress') border-red-500 @enderror">{{ old('adress', $contact->adress) }}</textarea>
                @error('adress')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                <!-- GROUPE -->
                <div>
                    <label for="group" class="form-label">
                        <i class="fas fa-layer-group text-orange-fonce mr-1"></i> Groupe
                    </label>
                    <select name="group" id="group" class="form-input bg-white @error('group') border-red-500 @enderror">
                        <option value="famille" {{ old('group', $contact->group) == 'famille' ? 'selected' : '' }}>Famille</option>
                        <option value="amis" {{ old('group', $contact->group) == 'amis' ? 'selected' : '' }}>Amis</option>
                        <option value="Collègue" {{ old('group', $contact->group) == 'Collègue' ? 'selected' : '' }}>Collègue</option>
                        <option value="autres" {{ old('group', $contact->group) == 'autres' ? 'selected' : '' }}>Autres</option>
                    </select>
                    @error('group')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- DATE DE NAISSANCE -->
                <div>
                    <label for="Birthday" class="form-label">
                        <i class="fas fa-birthday-cake text-orange-fonce mr-1"></i> Date de naissance
                    </label>
                    <input type="date" name="Birthday" id="Birthday" 
                           value="{{ old('Birthday', $contact->Birthday?->format('Y-m-d')) }}"
                           class="form-input @error('Birthday') border-red-500 @enderror">
                    @error('Birthday')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- NOTES -->
            <div class="mt-4">
                <label for="notes" class="form-label">
                    <i class="fas fa-sticky-note text-orange-fonce mr-1"></i> Notes
                </label>
                <textarea name="notes" id="notes" rows="3"
                          class="form-input @error('notes') border-red-500 @enderror">{{ old('notes', $contact->notes) }}</textarea>
                @error('notes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- FAVORIS -->
            <div class="mt-6 flex items-center gap-3">
                <input type="checkbox" name="favoris" value="1" id="favoris"
                       {{ old('favoris', $contact->favoris) ? 'checked' : '' }}
                       class="w-5 h-5 text-orange-fonce rounded border-gray-300 focus:ring-orange-fonce">
                <label for="favoris" class="text-bleu-fonce font-medium cursor-pointer flex items-center gap-1.5">
                    <i class="fas fa-star text-orange-fonce"></i> Marquer comme favori
                </label>
            </div>

            <!-- BOUTONS D'ACTION -->
            <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-end">
                <a href="{{ route('contacts.show', $contact->id) }}" class="btn-outline-primary text-center">
                    <i class="fas fa-times mr-2"></i> Annuler
                </a>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-check mr-2"></i> Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('contact-form');
    const dropdownBtn = document.getElementById('country-dropdown-btn');
    const dropdownMenu = document.getElementById('country-dropdown-menu');
    const countrySearch = document.getElementById('country-search');
    const countryList = document.getElementById('country-list');
    const selectedCodeSpan = document.getElementById('selected-code');
    const flagIconContainer = document.getElementById('flag-icon');
    const phoneInput = document.getElementById('phone_input');
    const phoneFull = document.getElementById('phone_full');
    const phoneHint = document.getElementById('phone-hint');
    const phoneError = document.getElementById('phone-error');

    const countries = [
        { name: "Cameroun", code: "+237", iso: "cm", digits: 9, placeholder: "6XXXXXXXX" },
        { name: "France", code: "+33", iso: "fr", digits: 9, placeholder: "612345678" },
        { name: "Côte d'Ivoire", code: "+225", iso: "ci", digits: 10, placeholder: "0701020304" },
        { name: "Sénégal", code: "+221", iso: "sn", digits: 9, placeholder: "771234567" },
        { name: "Congo (Brazzaville)", code: "+242", iso: "cg", digits: 9, placeholder: "061234567" },
        { name: "Congo (RDC)", code: "+243", iso: "cd", digits: 9, placeholder: "812345678" },
        { name: "Maroc", code: "+212", iso: "ma", digits: 9, placeholder: "612345678" },
        { name: "Gabon", code: "+241", iso: "ga", digits: 8, placeholder: "77123456" },
        { name: "Tchad", code: "+235", iso: "td", digits: 8, placeholder: "66123456" },
        { name: "Guinée", code: "+224", iso: "gn", digits: 9, placeholder: "621234567" },
        { name: "Mali", code: "+223", iso: "ml", digits: 8, placeholder: "66123456" },
        { name: "Burkina Faso", code: "+226", iso: "bf", digits: 8, placeholder: "70123456" },
        { name: "Togo", code: "+228", iso: "tg", digits: 8, placeholder: "90123456" },
        { name: "Bénin", code: "+229", iso: "bj", digits: 8, placeholder: "97123456" },
        { name: "Algérie", code: "+213", iso: "dz", digits: 9, placeholder: "551234567" },
        { name: "Tunisie", code: "+216", iso: "tn", digits: 8, placeholder: "20123456" },
        { name: "Égypte", code: "+20", iso: "eg", digits: 10, placeholder: "1012345678" },
        { name: "Belgique", code: "+32", iso: "be", digits: 9, placeholder: "471234567" },
        { name: "Suisse", code: "+41", iso: "ch", digits: 9, placeholder: "781234567" },
        { name: "Canada / É.-U.", code: "+1", iso: "us", digits: 10, placeholder: "2025550143" },
        { name: "Royaume-Uni", code: "+44", iso: "gb", digits: 10, placeholder: "7911123456" },
        { name: "Allemagne", code: "+49", iso: "de", digits: 11, placeholder: "15123456789" },
        { name: "Italie", code: "+39", iso: "it", digits: 10, placeholder: "3123456789" },
        { name: "Espagne", code: "+34", iso: "es", digits: 9, placeholder: "612345678" },
        { name: "Portugal", code: "+351", iso: "pt", digits: 9, placeholder: "912345678" },
        { name: "Chine", code: "+86", iso: "cn", digits: 11, placeholder: "13812345678" },
        { name: "Inde", code: "+91", iso: "in", digits: 10, placeholder: "9812345678" },
        { name: "Brésil", code: "+55", iso: "br", digits: 11, placeholder: "11912345678" },
        { name: "Turquie", code: "+90", iso: "tr", digits: 10, placeholder: "5012345678" },
        { name: "Émirats Arabes Unis", code: "+971", iso: "ae", digits: 9, placeholder: "501234567" }
    ];

    const flags = {
        cm: `<svg class="w-5 h-3.5 rounded-sm object-cover" viewBox="0 0 640 480"><rect width="213.3" height="480" fill="#007a5e"/><rect x="213.3" width="213.3" height="480" fill="#ce1126"/><rect x="426.6" width="213.3" height="480" fill="#fcd116"/><polygon points="320,210 330,240 360,240 335,260 345,290 320,270 295,290 305,260 280,240 310,240" fill="#fcd116"/></svg>`,
        fr: `<svg class="w-5 h-3.5 rounded-sm object-cover" viewBox="0 0 640 480"><rect width="213.3" height="480" fill="#002395"/><rect x="213.3" width="213.3" height="480" fill="#fff"/><rect x="426.6" width="213.3" height="480" fill="#ed2939"/></svg>`,
        ci: `<svg class="w-5 h-3.5 rounded-sm object-cover" viewBox="0 0 640 480"><rect width="213.3" height="480" fill="#f77f00"/><rect x="213.3" width="213.3" height="480" fill="#fff"/><rect x="426.6" width="213.3" height="480" fill="#009e60"/></svg>`,
        sn: `<svg class="w-5 h-3.5 rounded-sm object-cover" viewBox="0 0 640 480"><rect width="213.3" height="480" fill="#00853f"/><rect x="213.3" width="213.3" height="480" fill="#fdef42"/><rect x="426.6" width="213.3" height="480" fill="#e31b23"/><polygon points="320,190 330,220 360,220 335,240 345,270 320,250 295,270 305,240 280,220 310,220" fill="#00853f"/></svg>`,
        cg: `<svg class="w-5 h-3.5 rounded-sm object-cover" viewBox="0 0 640 480"><path fill="#009543" d="0 0h640v480H0z"/><path fill="#fbde4a" d="M0 480L640 0H0z"/><path fill="#dc241f" d="M640 0v480H0z"/></svg>`,
        cd: `<svg class="w-5 h-3.5 rounded-sm object-cover" viewBox="0 0 640 480"><path fill="#007fff" d="0 0h640v480H0z"/><path fill="#ce1126" d="M0 400L600 0h40v80L40 480H0z"/><path fill="#fcd116" d="M0 380L570 0h30v20L30 480H0zM40 480L640 80v20L60 480z"/><polygon points="80,60 90,90 120,90 95,110 105,140 80,120 55,140 65,110 40,90 70,90" fill="#fcd116"/></svg>`,
        ma: `<svg class="w-5 h-3.5 rounded-sm object-cover" viewBox="0 0 640 480"><path fill="#c1272d" d="0 0h640v480H0z"/><path stroke="#006233" stroke-width="12" fill="none" d="M320 160l35 108-92-67h114l-92 67z"/></svg>`,
        ga: `<svg class="w-5 h-3.5 rounded-sm object-cover" viewBox="0 0 640 480"><rect width="640" height="160" fill="#36a100"/><rect y="160" width="640" height="160" fill="#ffda44"/><rect y="320" width="640" height="160" fill="#006ea1"/></svg>`,
        td: `<svg class="w-5 h-3.5 rounded-sm object-cover" viewBox="0 0 640 480"><rect width="213.3" height="480" fill="#00205b"/><rect x="213.3" width="213.3" height="480" fill="#ffcd00"/><rect x="426.6" width="213.3" height="480" fill="#c8102e"/></svg>`,
        gn: `<svg class="w-5 h-3.5 rounded-sm object-cover" viewBox="0 0 640 480"><rect width="213.3" height="480" fill="#ce1126"/><rect x="213.3" width="213.3" height="480" fill="#fcd116"/><rect x="426.6" width="213.3" height="480" fill="#007a5e"/></svg>`,
        ml: `<svg class="w-5 h-3.5 rounded-sm object-cover" viewBox="0 0 640 480"><rect width="213.3" height="480" fill="#14b53a"/><rect x="213.3" width="213.3" height="480" fill="#fcd116"/><rect x="426.6" width="213.3" height="480" fill="#ce1126"/></svg>`,
        bf: `<svg class="w-5 h-3.5 rounded-sm object-cover" viewBox="0 0 640 480"><rect width="640" height="240" fill="#ef2b2d"/><rect y="240" width="640" height="240" fill="#009e49"/><polygon points="320,180 330,210 360,210 335,230 345,260 320,240 295,260 305,230 280,210 310,210" fill="#fcd116"/></svg>`,
        tg: `<svg class="w-5 h-3.5 rounded-sm object-cover" viewBox="0 0 640 480"><rect width="640" height="480" fill="#006a4e"/><rect y="96" width="640" height="96" fill="#ffce00"/><rect y="288" width="640" height="96" fill="#ffce00"/><rect width="288" height="288" fill="#d21034"/><polygon points="144,80 154,110 184,110 159,130 169,160 144,140 119,160 129,130 104,110 134,110" fill="#fff"/></svg>`,
        bj: `<svg class="w-5 h-3.5 rounded-sm object-cover" viewBox="0 0 640 480"><rect width="256" height="480" fill="#008751"/><rect x="256" width="384" height="240" fill="#fcd116"/><rect x="256" y="240" width="384" height="240" fill="#e8112d"/></svg>`,
        dz: `<svg class="w-5 h-3.5 rounded-sm object-cover" viewBox="0 0 640 480"><rect width="320" height="480" fill="#006233"/><rect x="320" width="320" height="480" fill="#fff"/><path fill="#d21034" d="M360 240a80 80 0 1 1-120-69 100 100 0 1 0 0 138 80 80 0 0 1 120-69z"/><polygon points="360,210 367,230 388,230 371,243 377,263 360,251 343,263 349,243 332,230 353,230" fill="#d21034"/></svg>`,
        tn: `<svg class="w-5 h-3.5 rounded-sm object-cover" viewBox="0 0 640 480"><rect width="640" height="480" fill="#e70013"/><circle cx="320" cy="240" r="120" fill="#fff"/><path fill="#e70013" d="M340 240a60 60 0 1 1-90-52 75 75 0 1 0 0 104 60 60 0 0 1 90-52z"/><polygon points="335,215 340,230 355,230 343,240 347,255 335,246 323,255 327,240 315,230 330,230" fill="#e70013"/></svg>`,
        eg: `<svg class="w-5 h-3.5 rounded-sm object-cover" viewBox="0 0 640 480"><rect width="640" height="160" fill="#ce1126"/><rect y="160" width="640" height="160" fill="#fff"/><rect y="320" width="640" height="160" fill="#000"/></svg>`,
        be: `<svg class="w-5 h-3.5 rounded-sm object-cover" viewBox="0 0 640 480"><rect width="213.3" height="480" fill="#000"/><rect x="213.3" width="213.3" height="480" fill="#ffd100"/><rect x="426.6" width="213.3" height="480" fill="#ff0f21"/></svg>`,
        ch: `<svg class="w-5 h-3.5 rounded-sm object-cover" viewBox="0 0 640 480"><rect width="640" height="480" fill="#d52b1e"/><rect x="260" y="100" width="120" height="280" fill="#fff"/><rect x="180" y="180" width="280" height="120" fill="#fff"/></svg>`,
        us: `<svg class="w-5 h-3.5 rounded-sm object-cover" viewBox="0 0 640 480"><path fill="#bd3d44" d="0 0h640v480H0z"/><path stroke="#fff" stroke-width="37" d="M0 55h640M0 129h640M0 203h640M0 277h640M0 351h640M0 425h640"/><path fill="#192f5d" d="0 0h256v258H0z"/></svg>`,
        gb: `<svg class="w-5 h-3.5 rounded-sm object-cover" viewBox="0 0 640 480"><path fill="#012169" d="0 0h640v480H0z"/><path stroke="#fff" stroke-width="60" d="M0 0l640 480M640 0L0 480M320 0v480M0 240h640"/><path stroke="#C8102E" stroke-width="40" d="M320 0v480M0 240h640"/></svg>`,
        de: `<svg class="w-5 h-3.5 rounded-sm object-cover" viewBox="0 0 640 480"><rect width="640" height="160" fill="#000"/><rect y="160" width="640" height="160" fill="#dd0000"/><rect y="320" width="640" height="160" fill="#ffce00"/></svg>`,
        it: `<svg class="w-5 h-3.5 rounded-sm object-cover" viewBox="0 0 640 480"><rect width="213.3" height="480" fill="#009246"/><rect x="213.3" width="213.3" height="480" fill="#fff"/><rect x="426.6" width="213.3" height="480" fill="#ce2b37"/></svg>`,
        es: `<svg class="w-5 h-3.5 rounded-sm object-cover" viewBox="0 0 640 480"><rect width="640" height="120" fill="#aa151b"/><rect y="120" width="640" height="240" fill="#f1bf00"/><rect y="360" width="640" height="120" fill="#aa151b"/></svg>`,
        pt: `<svg class="w-5 h-3.5 rounded-sm object-cover" viewBox="0 0 640 480"><rect width="256" height="480" fill="#046a38"/><rect x="256" width="384" height="480" fill="#da291c"/></svg>`,
        cn: `<svg class="w-5 h-3.5 rounded-sm object-cover" viewBox="0 0 640 480"><rect width="640" height="480" fill="#ee1c25"/></svg>`,
        in: `<svg class="w-5 h-3.5 rounded-sm object-cover" viewBox="0 0 640 480"><rect width="640" height="160" fill="#ff9933"/><rect y="160" width="640" height="160" fill="#fff"/><rect y="320" width="640" height="160" fill="#128807"/></svg>`,
        br: `<svg class="w-5 h-3.5 rounded-sm object-cover" viewBox="0 0 640 480"><rect width="640" height="480" fill="#009b3a"/><polygon points="320,48 592,240 320,432 48,240" fill="#fedf00"/></svg>`,
        tr: `<svg class="w-5 h-3.5 rounded-sm object-cover" viewBox="0 0 640 480"><rect width="640" height="480" fill="#e30a17"/></svg>`,
        ae: `<svg class="w-5 h-3.5 rounded-sm object-cover" viewBox="0 0 640 480"><rect width="640" height="160" fill="#00732f"/><rect y="160" width="640" height="160" fill="#fff"/><rect y="320" width="640" height="160" fill="#000"/><rect width="192" height="480" fill="#ff0000"/></svg>`
    };

    let selectedCountry = countries[0];

    function renderCountryList(filter = '') {
        countryList.innerHTML = '';
        const searchVal = filter.toLowerCase();

        const filtered = countries.filter(c => 
            c.name.toLowerCase().includes(searchVal) || 
            c.code.includes(searchVal)
        );

        if (filtered.length === 0) {
            countryList.innerHTML = `<li class="p-3 text-xs text-gray-500 text-center">Aucun pays trouvé</li>`;
            return;
        }

        filtered.forEach(c => {
            const li = document.createElement('li');
            li.className = 'flex items-center justify-between px-3 py-2 hover:bg-gray-100 cursor-pointer text-xs transition-colors';
            const flagSvg = flags[c.iso] || '<i class="fas fa-globe text-gray-400"></i>';
            li.innerHTML = `
                <div class="flex items-center gap-2">
                    <span class="w-5 h-3.5 flex items-center justify-center">${flagSvg}</span>
                    <span class="font-medium text-gray-700">${c.name}</span>
                </div>
                <span class="font-bold text-gray-500">${c.code}</span>
            `;
            li.addEventListener('click', () => {
                selectCountry(c);
                dropdownMenu.classList.add('hidden');
            });
            countryList.appendChild(li);
        });
    }

    function selectCountry(country) {
        selectedCountry = country;
        selectedCodeSpan.textContent = country.code;
        flagIconContainer.innerHTML = flags[country.iso] || '<i class="fas fa-globe text-gray-400"></i>';
        phoneInput.placeholder = country.placeholder;
        phoneInput.maxLength = country.digits;
        updatePhoneValidation();
    }

    function updatePhoneValidation() {
        const requiredDigits = selectedCountry.digits;
        let cleanVal = phoneInput.value.replace(/\D/g, '');

        if (cleanVal.length > requiredDigits) {
            cleanVal = cleanVal.substring(0, requiredDigits);
        }
        phoneInput.value = cleanVal;

        phoneHint.textContent = `Exactement ${requiredDigits} chiffres attendus.`;

        if (cleanVal.length === 0) {
            phoneError.classList.add('hidden');
            phoneFull.value = '';
            return true;
        }

        if (cleanVal.length === requiredDigits) {
            phoneError.classList.add('hidden');
            phoneFull.value = selectedCountry.code + cleanVal;
            return true;
        } else {
            phoneError.classList.remove('hidden');
            phoneError.textContent = `Incomplet (${cleanVal.length}/${requiredDigits} chiffres)`;
            phoneFull.value = '';
            return false;
        }
    }

    dropdownBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        dropdownMenu.classList.toggle('hidden');
        if (!dropdownMenu.classList.contains('hidden')) {
            countrySearch.focus();
            countrySearch.value = '';
            renderCountryList();
        }
    });

    countrySearch.addEventListener('input', (e) => {
        renderCountryList(e.target.value);
    });

    document.addEventListener('click', (e) => {
        if (!dropdownMenu.contains(e.target) && !dropdownBtn.contains(e.target)) {
            dropdownMenu.classList.add('hidden');
        }
    });

    // Pré-remplissage en cas de modification
    if (phoneFull.value) {
        const fullVal = phoneFull.value;
        const matchedCountry = countries.find(c => fullVal.startsWith(c.code));
        if (matchedCountry) {
            selectCountry(matchedCountry);
            phoneInput.value = fullVal.replace(matchedCountry.code, '');
        } else {
            selectCountry(countries[0]);
        }
    } else {
        selectCountry(countries[0]);
    }

    phoneInput.addEventListener('input', updatePhoneValidation);

    form.addEventListener('submit', function (e) {
        if (!updatePhoneValidation()) {
            e.preventDefault();
            phoneInput.focus();
        }
    });
});
</script>
@endsection