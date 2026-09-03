@extends('layouts.app')

@section('title', 'Ajouter un contact')

@section('header-title', 'Nouveau contact')
@section('header-subtitle', 'Ajoutez un nouveau contact à votre carnet')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="card p-6 md:p-8">
        <form method="POST" action="{{ route('contacts.store') }}" id="contact-form">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- NOM -->
                <div>
                    <label for="name" class="form-label">
                        <i class="fas fa-user text-orange-fonce mr-1"></i> <span class="text-red-500">*</span> Nom
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                           class="form-input @error('name') border-red-500 @enderror"
                           placeholder="Dupont" required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- PRÉNOM -->
                <div>
                    <label for="surname" class="form-label">
                        <i class="fas fa-user-tag text-orange-fonce mr-1"></i> Prénom
                    </label>
                    <input type="text" name="surname" id="surname" value="{{ old('surname') }}"
                           class="form-input @error('surname') border-red-500 @enderror"
                           placeholder="Jean">
                    @error('surname')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- TÉLÉPHONE AVEC RECHERCHE DE PAYS (193 PAYS UN) -->
                <div>
                    <label for="phone_input" class="form-label">
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
                                   class="w-full px-3 py-2 text-sm text-gray-800 outline-none"
                                   placeholder="6XXXXXXXX">
                        </div>

                        <!-- Menu déroulant avec recherche -->
                        <div id="country-dropdown-menu" class="hidden absolute z-50 left-0 top-full mt-1 w-80 bg-white border border-gray-200 rounded-lg shadow-lg overflow-hidden">
                            <div class="p-2 border-b border-gray-100 bg-gray-50">
                                <div class="relative">
                                    <i class="fas fa-search absolute left-3 top-2.5 text-xs text-gray-400"></i>
                                    <input type="text" id="country-search" class="w-full pl-8 pr-3 py-1.5 text-xs border border-gray-300 rounded-md outline-none focus:border-bleu-fonce" placeholder="Rechercher un pays ou un code...">
                                </div>
                            </div>
                            <ul id="country-list" class="max-h-60 overflow-y-auto divide-y divide-gray-50">
                                <!-- Liste des 193 pays générée dynamiquement via JS -->
                            </ul>
                        </div>
                    </div>

                    <input type="hidden" name="phone" id="phone_full" value="{{ old('phone') }}">

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
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                           class="form-input @error('email') border-red-500 @enderror"
                           placeholder="jean@exemple.com">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- ADRESSE -->
            <div class="mt-4">
                <label for="address" class="form-label">
                    <i class="fas fa-map-marker-alt text-orange-fonce mr-1"></i> Adresse
                </label>
                <textarea name="address" id="address" rows="2"
                          class="form-input @error('address') border-red-500 @enderror"
                          placeholder="123 rue de Paris, 75001 Paris">{{ old('address') }}</textarea>
                @error('address')
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
                        <option value="famille" {{ old('group') == 'famille' ? 'selected' : '' }}>Famille</option>
                        <option value="amis" {{ old('group') == 'amis' ? 'selected' : '' }}>Amis</option>
                        <option value="collegue" {{ old('group') == 'collegue' ? 'selected' : '' }}>Collègue</option>
                        <option value="autres" {{ old('group', 'autres') == 'autres' ? 'selected' : '' }}>Autres</option>
                    </select>
                    @error('group')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- DATE DE NAISSANCE -->
                <div>
                    <label for="birthday" class="form-label">
                        <i class="fas fa-birthday-cake text-orange-fonce mr-1"></i> Date de naissance
                    </label>
                    <input type="date" name="birthday" id="birthday" value="{{ old('birthday') }}"
                           class="form-input @error('birthday') border-red-500 @enderror">
                    @error('birthday')
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
                          class="form-input @error('notes') border-red-500 @enderror"
                          placeholder="Informations complémentaires...">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- FAVORIS -->
            <div class="mt-6 flex items-center gap-3">
                <input type="checkbox" name="favoris" value="1" id="favoris"
                       {{ old('favoris') ? 'checked' : '' }}
                       class="w-5 h-5 text-orange-fonce rounded border-gray-300 focus:ring-orange-fonce">
                <label for="favoris" class="text-bleu-fonce font-medium cursor-pointer flex items-center gap-1.5">
                    <i class="fas fa-star text-orange-fonce"></i> Marquer comme favori
                </label>
            </div>

            <!-- BOUTONS D'ACTION -->
            <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-end">
                <a href="{{ route('contacts.index') }}" class="btn-outline-primary text-center">
                    <i class="fas fa-times mr-2"></i> Annuler
                </a>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2"></i> Enregistrer
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

    // LISTE DES 193 PAYS MEMBRES DE L'ONU ET INDICATIFS
    const countries = [
        { name: "Afghanistan", code: "+93", iso: "af", digits: 9, placeholder: "701234567" },
        { name: "Afrique du Sud", code: "+27", iso: "za", digits: 9, placeholder: "821234567" },
        { name: "Albanie", code: "+355", iso: "al", digits: 9, placeholder: "671234567" },
        { name: "Algérie", code: "+213", iso: "dz", digits: 9, placeholder: "551234567" },
        { name: "Allemagne", code: "+49", iso: "de", digits: 11, placeholder: "15123456789" },
        { name: "Andorre", code: "+376", iso: "ad", digits: 6, placeholder: "312345" },
        { name: "Angola", code: "+244", iso: "ao", digits: 9, placeholder: "912345678" },
        { name: "Antigua-et-Barbuda", code: "+1268", iso: "ag", digits: 7, placeholder: "4641234" },
        { name: "Arabie Saoudite", code: "+966", iso: "sa", digits: 9, placeholder: "512345678" },
        { name: "Argentine", code: "+54", iso: "ar", digits: 10, placeholder: "91123456789" },
        { name: "Arménie", code: "+374", iso: "am", digits: 8, placeholder: "77123456" },
        { name: "Australie", code: "+61", iso: "au", digits: 9, placeholder: "412345678" },
        { name: "Autriche", code: "+43", iso: "at", digits: 10, placeholder: "6641234567" },
        { name: "Azerbaïdjan", code: "+994", iso: "az", digits: 9, placeholder: "501234567" },
        { name: "Bahamas", code: "+1242", iso: "bs", digits: 7, placeholder: "3571234" },
        { name: "Bahreïn", code: "+973", iso: "bh", digits: 8, placeholder: "36123456" },
        { name: "Bangladesh", code: "+880", iso: "bd", digits: 10, placeholder: "1712345678" },
        { name: "Barbade", code: "+1246", iso: "bb", digits: 7, placeholder: "2301234" },
        { name: "Belgique", code: "+32", iso: "be", digits: 9, placeholder: "471234567" },
        { name: "Belize", code: "+501", iso: "bz", digits: 7, placeholder: "6101234" },
        { name: "Bénin", code: "+229", iso: "bj", digits: 8, placeholder: "97123456" },
        { name: "Bhoutan", code: "+975", iso: "bt", digits: 8, placeholder: "17123456" },
        { name: "Biélorussie", code: "+375", iso: "by", digits: 9, placeholder: "291234567" },
        { name: "Birmanie (Myanmar)", code: "+95", iso: "mm", digits: 9, placeholder: "912345678" },
        { name: "Bolivie", code: "+591", iso: "bo", digits: 8, placeholder: "71234567" },
        { name: "Bosnie-Herzégovine", code: "+387", iso: "ba", digits: 8, placeholder: "61123456" },
        { name: "Botswana", code: "+267", iso: "bw", digits: 8, placeholder: "71123456" },
        { name: "Brésil", code: "+55", iso: "br", digits: 11, placeholder: "11912345678" },
        { name: "Brunei", code: "+673", iso: "bn", digits: 7, placeholder: "7123456" },
        { name: "Bulgarie", code: "+359", iso: "bg", digits: 9, placeholder: "871234567" },
        { name: "Burkina Faso", code: "+226", iso: "bf", digits: 8, placeholder: "70123456" },
        { name: "Burundi", code: "+257", iso: "bi", digits: 8, placeholder: "79123456" },
        { name: "Cambodge", code: "+855", iso: "kh", digits: 8, placeholder: "12345678" },
        { name: "Cameroun", code: "+237", iso: "cm", digits: 9, placeholder: "6XXXXXXXX" },
        { name: "Canada", code: "+1", iso: "ca", digits: 10, placeholder: "2042345678" },
        { name: "Cap-Vert", code: "+238", iso: "cv", digits: 7, placeholder: "9123456" },
        { name: "Centrafrique", code: "+236", iso: "cf", digits: 8, placeholder: "75123456" },
        { name: "Chili", code: "+56", iso: "cl", digits: 9, placeholder: "912345678" },
        { name: "Chine", code: "+86", iso: "cn", digits: 11, placeholder: "13812345678" },
        { name: "Chypre", code: "+357", iso: "cy", digits: 8, placeholder: "96123456" },
        { name: "Colombie", code: "+57", iso: "co", digits: 10, placeholder: "3001234567" },
        { name: "Comores", code: "+269", iso: "km", digits: 7, placeholder: "3212345" },
        { name: "Congo (Brazzaville)", code: "+242", iso: "cg", digits: 9, placeholder: "061234567" },
        { name: "Congo (RDC)", code: "+243", iso: "cd", digits: 9, placeholder: "812345678" },
        { name: "Corée du Nord", code: "+850", iso: "kp", digits: 8, placeholder: "19123456" },
        { name: "Corée du Sud", code: "+82", iso: "kr", digits: 10, placeholder: "1012345678" },
        { name: "Costa Rica", code: "+506", iso: "cr", digits: 8, placeholder: "83123456" },
        { name: "Côte d'Ivoire", code: "+225", iso: "ci", digits: 10, placeholder: "0701020304" },
        { name: "Croatie", code: "+385", iso: "hr", digits: 9, placeholder: "911234567" },
        { name: "Cuba", code: "+53", iso: "cu", digits: 8, placeholder: "51234567" },
        { name: "Danemark", code: "+45", iso: "dk", digits: 8, placeholder: "20123456" },
        { name: "Djibouti", code: "+253", iso: "dj", digits: 8, placeholder: "77123456" },
        { name: "Dominique", code: "+1767", iso: "dm", digits: 7, placeholder: "2351234" },
        { name: "Égypte", code: "+20", iso: "eg", digits: 10, placeholder: "1012345678" },
        { name: "Émirats Arabes Unis", code: "+971", iso: "ae", digits: 9, placeholder: "501234567" },
        { name: "Équateur", code: "+593", iso: "ec", digits: 9, placeholder: "991234567" },
        { name: "Érythrée", code: "+291", iso: "er", digits: 7, placeholder: "7123456" },
        { name: "Espagne", code: "+34", iso: "es", digits: 9, placeholder: "612345678" },
        { name: "Estonie", code: "+372", iso: "ee", digits: 8, placeholder: "51234567" },
        { name: "Eswatini (Swaziland)", code: "+268", iso: "sz", digits: 8, placeholder: "76123456" },
        { name: "États-Unis", code: "+1", iso: "us", digits: 10, placeholder: "2025550143" },
        { name: "Éthiopie", code: "+251", iso: "et", digits: 9, placeholder: "911234567" },
        { name: "Fidji", code: "+679", iso: "fj", digits: 7, placeholder: "7012345" },
        { name: "Finlande", code: "+358", iso: "fi", digits: 9, placeholder: "401234567" },
        { name: "France", code: "+33", iso: "fr", digits: 9, placeholder: "612345678" },
        { name: "Gabon", code: "+241", iso: "ga", digits: 8, placeholder: "77123456" },
        { name: "Gambie", code: "+220", iso: "gm", digits: 7, placeholder: "7012345" },
        { name: "Géorgie", code: "+995", iso: "ge", digits: 9, placeholder: "555123456" },
        { name: "Ghana", code: "+233", iso: "gh", digits: 9, placeholder: "241234567" },
        { name: "Grèce", code: "+30", iso: "gr", digits: 10, placeholder: "6912345678" },
        { name: "Grenade", code: "+1473", iso: "gd", digits: 7, placeholder: "4031234" },
        { name: "Guatemala", code: "+502", iso: "gt", digits: 8, placeholder: "51234567" },
        { name: "Guinée", code: "+224", iso: "gn", digits: 9, placeholder: "621234567" },
        { name: "Guinée-Bissau", code: "+245", iso: "gw", digits: 9, placeholder: "955123456" },
        { name: "Guinée équatoriale", code: "+240", iso: "gq", digits: 9, placeholder: "222123456" },
        { name: "Guyana", code: "+592", iso: "gy", digits: 7, placeholder: "6091234" },
        { name: "Haïti", code: "+509", iso: "ht", digits: 8, placeholder: "34123456" },
        { name: "Honduras", code: "+504", iso: "hn", digits: 8, placeholder: "91234567" },
        { name: "Hongrie", code: "+36", iso: "hu", digits: 9, placeholder: "201234567" },
        { name: "Inde", code: "+91", iso: "in", digits: 10, placeholder: "9812345678" },
        { name: "Indonésie", code: "+62", iso: "id", digits: 11, placeholder: "81234567890" },
        { name: "Irak", code: "+964", iso: "iq", digits: 10, placeholder: "7901234567" },
        { name: "Iran", code: "+98", iso: "ir", digits: 10, placeholder: "9123456789" },
        { name: "Irlande", code: "+353", iso: "ie", digits: 9, placeholder: "851234567" },
        { name: "Islande", code: "+354", iso: "is", digits: 7, placeholder: "6123456" },
        { name: "Israël", code: "+972", iso: "il", digits: 9, placeholder: "501234567" },
        { name: "Italie", code: "+39", iso: "it", digits: 10, placeholder: "3123456789" },
        { name: "Jamaïque", code: "+1876", iso: "jm", digits: 7, placeholder: "8123456" },
        { name: "Japon", code: "+81", iso: "jp", digits: 10, placeholder: "9012345678" },
        { name: "Jordanie", code: "+962", iso: "jo", digits: 9, placeholder: "791234567" },
        { name: "Kazakhstan", code: "+7", iso: "kz", digits: 10, placeholder: "7012345678" },
        { name: "Kenya", code: "+254", iso: "ke", digits: 9, placeholder: "712345678" },
        { name: "Kirghizistan", code: "+996", iso: "kg", digits: 9, placeholder: "550123456" },
        { name: "Kiribati", code: "+686", iso: "ki", digits: 8, placeholder: "72012345" },
        { name: "Koweït", code: "+965", iso: "kw", digits: 8, placeholder: "91234567" },
        { name: "Laos", code: "+856", iso: "la", digits: 10, placeholder: "2012345678" },
        { name: "Lesotho", code: "+266", iso: "ls", digits: 8, placeholder: "58123456" },
        { name: "Lettonie", code: "+371", iso: "lv", digits: 8, placeholder: "21234567" },
        { name: "Liban", code: "+961", iso: "lb", digits: 8, placeholder: "71123456" },
        { name: "Libéria", code: "+231", iso: "lr", digits: 8, placeholder: "77123456" },
        { name: "Libye", code: "+218", iso: "ly", digits: 9, placeholder: "911234567" },
        { name: "Liechtenstein", code: "+423", iso: "li", digits: 7, placeholder: "6612345" },
        { name: "Lituanie", code: "+370", iso: "lt", digits: 8, placeholder: "61234567" },
        { name: "Luxembourg", code: "+352", iso: "lu", digits: 9, placeholder: "621123456" },
        { name: "Macédoine du Nord", code: "+389", iso: "mk", digits: 8, placeholder: "70123456" },
        { name: "Madagascar", code: "+261", iso: "mg", digits: 9, placeholder: "321234567" },
        { name: "Malaisie", code: "+60", iso: "my", digits: 9, placeholder: "123456789" },
        { name: "Malawi", code: "+265", iso: "mw", digits: 9, placeholder: "991234567" },
        { name: "Maldives", code: "+960", iso: "mv", digits: 7, placeholder: "7712345" },
        { name: "Mali", code: "+223", iso: "ml", digits: 8, placeholder: "66123456" },
        { name: "Malte", code: "+356", iso: "mt", digits: 8, placeholder: "99123456" },
        { name: "Maroc", code: "+212", iso: "ma", digits: 9, placeholder: "612345678" },
        { name: "Marshall", code: "+692", iso: "mh", digits: 7, placeholder: "2351234" },
        { name: "Maurice", code: "+230", iso: "mu", digits: 8, placeholder: "51234567" },
        { name: "Mauritanie", code: "+222", iso: "mr", digits: 8, placeholder: "46123456" },
        { name: "Mexique", code: "+52", iso: "mx", digits: 10, placeholder: "5512345678" },
        { name: "Micronésie", code: "+691", iso: "fm", digits: 7, placeholder: "9201234" },
        { name: "Moldavie", code: "+373", iso: "md", digits: 8, placeholder: "62123456" },
        { name: "Monaco", code: "+377", iso: "mc", digits: 8, placeholder: "61234567" },
        { name: "Mongolie", code: "+976", iso: "mn", digits: 8, placeholder: "88123456" },
        { name: "Monténégro", code: "+382", iso: "me", digits: 8, placeholder: "67123456" },
        { name: "Mozambique", code: "+258", iso: "mz", digits: 9, placeholder: "821234567" },
        { name: "Namibie", code: "+264", iso: "na", digits: 9, placeholder: "811234567" },
        { name: "Nauru", code: "+674", iso: "nr", digits: 7, placeholder: "5551234" },
        { name: "Népal", code: "+977", iso: "np", digits: 10, placeholder: "9812345678" },
        { name: "Nicaragua", code: "+505", iso: "ni", digits: 8, placeholder: "87123456" },
        { name: "Niger", code: "+227", iso: "ne", digits: 8, placeholder: "96123456" },
        { name: "Nigeria", code: "+234", iso: "ng", digits: 10, placeholder: "8031234567" },
        { name: "Norvège", code: "+47", iso: "no", digits: 8, placeholder: "41234567" },
        { name: "Nouvelle-Zélande", code: "+64", iso: "nz", digits: 9, placeholder: "211234567" },
        { name: "Oman", code: "+968", iso: "om", digits: 8, placeholder: "91234567" },
        { name: "Ouganda", code: "+256", iso: "ug", digits: 9, placeholder: "771234567" },
        { name: "Ouzbékistan", code: "+998", iso: "uz", digits: 9, placeholder: "901234567" },
        { name: "Pakistan", code: "+92", iso: "pk", digits: 10, placeholder: "3001234567" },
        { name: "Palaos", code: "+680", iso: "pw", digits: 7, placeholder: "7751234" },
        { name: "Palestine", code: "+970", iso: "ps", digits: 9, placeholder: "599123456" },
        { name: "Panama", code: "+507", iso: "pa", digits: 8, placeholder: "61234567" },
        { name: "Papouasie-Nouvelle-Guinée", code: "+675", iso: "pg", digits: 8, placeholder: "70123456" },
        { name: "Paraguay", code: "+595", iso: "py", digits: 9, placeholder: "981234567" },
        { name: "Pays-Bas", code: "+31", iso: "nl", digits: 9, placeholder: "612345678" },
        { name: "Pérou", code: "+51", iso: "pe", digits: 9, placeholder: "912345678" },
        { name: "Philippines", code: "+63", iso: "ph", digits: 10, placeholder: "9171234567" },
        { name: "Pologne", code: "+48", iso: "pl", digits: 9, placeholder: "501234567" },
        { name: "Portugal", code: "+351", iso: "pt", digits: 9, placeholder: "912345678" },
        { name: "Qatar", code: "+974", iso: "qa", digits: 8, placeholder: "33123456" },
        { name: "République Dominicaine", code: "+1809", iso: "do", digits: 7, placeholder: "2201234" },
        { name: "République Tchèque", code: "+420", iso: "cz", digits: 9, placeholder: "601123456" },
        { name: "Roumanie", code: "+40", iso: "ro", digits: 9, placeholder: "712345678" },
        { name: "Royaume-Uni", code: "+44", iso: "gb", digits: 10, placeholder: "7911123456" },
        { name: "Russie", code: "+7", iso: "ru", digits: 10, placeholder: "9123456789" },
        { name: "Rwanda", code: "+250", iso: "rw", digits: 9, placeholder: "781234567" },
        { name: "Saint-Christophe-et-Niévès", code: "+1869", iso: "kn", digits: 7, placeholder: "4651234" },
        { name: "Sainte-Lucie", code: "+1758", iso: "lc", digits: 7, placeholder: "2851234" },
        { name: "Saint-Marin", code: "+378", iso: "sm", digits: 10, placeholder: "668712345" },
        { name: "Saint-Vincent-et-les-Grenadines", code: "+1784", iso: "vc", digits: 7, placeholder: "4541234" },
        { name: "Salomon", code: "+677", iso: "sb", digits: 7, placeholder: "7412345" },
        { name: "Samoa", code: "+685", iso: "ws", digits: 7, placeholder: "7212345" },
        { name: "São Tomé-et-Príncipe", code: "+239", iso: "st", digits: 7, placeholder: "9812345" },
        { name: "Sénégal", code: "+221", iso: "sn", digits: 9, placeholder: "771234567" },
        { name: "Serbie", code: "+381", iso: "rs", digits: 9, placeholder: "611234567" },
        { name: "Seychelles", code: "+248", iso: "sc", digits: 7, placeholder: "2512345" },
        { name: "Sierra Leone", code: "+232", iso: "sl", digits: 8, placeholder: "76123456" },
        { name: "Singapour", code: "+65", iso: "sg", digits: 8, placeholder: "81234567" },
        { name: "Slovaquie", code: "+421", iso: "sk", digits: 9, placeholder: "912345678" },
        { name: "Slovénie", code: "+386", iso: "si", digits: 8, placeholder: "31234567" },
        { name: "Somalie", code: "+252", iso: "so", digits: 8, placeholder: "61123456" },
        { name: "Soudan", code: "+249", iso: "sd", digits: 9, placeholder: "912345678" },
        { name: "Soudan du Sud", code: "+211", iso: "ss", digits: 9, placeholder: "912345678" },
        { name: "Sri Lanka", code: "+94", iso: "lk", digits: 9, placeholder: "712345678" },
        { name: "Suède", code: "+46", iso: "se", digits: 9, placeholder: "701234567" },
        { name: "Suisse", code: "+41", iso: "ch", digits: 9, placeholder: "781234567" },
        { name: "Suriname", code: "+597", iso: "sr", digits: 7, placeholder: "7412345" },
        { name: "Syrie", code: "+963", iso: "sy", digits: 9, placeholder: "931234567" },
        { name: "Tadjikistan", code: "+992", iso: "tj", digits: 9, placeholder: "918123456" },
        { name: "Tanzanie", code: "+255", iso: "tz", digits: 9, placeholder: "712345678" },
        { name: "Tchad", code: "+235", iso: "td", digits: 8, placeholder: "66123456" },
        { name: "Thaïlande", code: "+66", iso: "th", digits: 9, placeholder: "812345678" },
        { name: "Timor oriental", code: "+670", iso: "tl", digits: 8, placeholder: "77123456" },
        { name: "Togo", code: "+228", iso: "tg", digits: 8, placeholder: "90123456" },
        { name: "Tonga", code: "+676", iso: "to", digits: 5, placeholder: "77123" },
        { name: "Trinité-et-Tobago", code: "+1868", iso: "tt", digits: 7, placeholder: "6801234" },
        { name: "Tunisie", code: "+216", iso: "tn", digits: 8, placeholder: "20123456" },
        { name: "Turkménistan", code: "+993", iso: "tm", digits: 8, placeholder: "65123456" },
        { name: "Turquie", code: "+90", iso: "tr", digits: 10, placeholder: "5012345678" },
        { name: "Tuvalu", code: "+688", iso: "tv", digits: 5, placeholder: "90123" },
        { name: "Ukraine", code: "+380", iso: "ua", digits: 9, placeholder: "501234567" },
        { name: "Uruguay", code: "+598", iso: "uy", digits: 8, placeholder: "99123456" },
        { name: "Vanuatu", code: "+678", iso: "vu", digits: 7, placeholder: "5912345" },
        { name: "Vatican", code: "+39", iso: "va", digits: 10, placeholder: "0669812345" },
        { name: "Vénézuéla", code: "+58", iso: "ve", digits: 10, placeholder: "4121234567" },
        { name: "Viêt Nam", code: "+84", iso: "vn", digits: 9, placeholder: "912345678" },
        { name: "Yémen", code: "+967", iso: "ye", digits: 9, placeholder: "712345678" },
        { name: "Zambie", code: "+260", iso: "zm", digits: 9, placeholder: "955123456" },
        { name: "Zimbabwe", code: "+263", iso: "zw", digits: 9, placeholder: "712345678" }
    ];

    let selectedCountry = countries.find(c => c.iso === 'cm') || countries[0];

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

            const nameSpan = document.createElement('span');
            nameSpan.className = 'font-medium text-gray-700';
            nameSpan.textContent = c.name;

            const codeSpan = document.createElement('span');
            codeSpan.className = 'font-bold text-gray-500';
            codeSpan.textContent = c.code;

            const flagImg = `<img src="https://flagcdn.com/20x15/${c.iso}.png" width="20" height="15" alt="${c.name}" class="rounded-sm object-cover flex-shrink-0">`;

            const leftContainer = document.createElement('div');
            leftContainer.className = 'flex items-center gap-2';
            leftContainer.innerHTML = flagImg;
            leftContainer.appendChild(nameSpan);

            li.appendChild(leftContainer);
            li.appendChild(codeSpan);

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
        flagIconContainer.innerHTML = `<img src="https://flagcdn.com/20x15/${country.iso}.png" width="20" height="15" alt="${country.name}" class="rounded-sm object-cover">`;
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

        phoneHint.textContent = `${selectedCountry.name} : ${requiredDigits} chiffres attendus.`;

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
            phoneError.textContent = `Incomplet (${cleanVal.length}/${requiredDigits})`;
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

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            dropdownMenu.classList.add('hidden');
        }
    });

    if (phoneFull.value) {
        const fullVal = phoneFull.value;
        const matchedCountry = countries.find(c => fullVal.startsWith(c.code));
        if (matchedCountry) {
            selectCountry(matchedCountry);
            phoneInput.value = fullVal.replace(matchedCountry.code, '');
        } else {
            selectCountry(countries.find(c => c.iso === 'cm'));
        }
    } else {
        selectCountry(countries.find(c => c.iso === 'cm'));
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