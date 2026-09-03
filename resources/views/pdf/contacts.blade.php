<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Contacts</title>
    <style>
        @page {
            margin: 15px;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #333333;
            background-color: #ffffff;
        }

        /* En-tête global */
        .pdf-header {
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #284225;
        }
        .pdf-title {
            font-size: 20px;
            font-weight: bold;
            color: #284225;
            margin: 0;
        }
        .pdf-subtitle {
            font-size: 10px;
            color: #666666;
            margin-top: 4px;
        }

        /* Structure de la Table */
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        /* En-tête vert foncé stylisé */
        thead th {
            background-color: #284225;
            color: #ffffff;
            font-weight: 600;
            text-align: left;
            padding: 10px 8px;
            font-size: 11px;
            border-right: 1px solid #3d5e39;
        }
        thead th:last-child {
            border-right: none;
            background-color: #a82a2a; /* Colonne d'action / statut comme sur l'image */
        }

        /* Lignes & Cellules */
        tbody tr {
            border-bottom: 1px solid #e5e7eb;
        }
        tbody td {
            padding: 8px;
            vertical-align: middle;
            color: #374151;
        }

        /* Badges / Pills */
        .pill {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 500;
            background-color: #eef2f6;
            color: #1f2937;
        }
        .pill-group {
            background-color: #e0e7ff;
            color: #3730a3;
        }
        .pill-address {
            background-color: #f3f4f6;
            color: #4b5563;
        }

        /* Liens et texte accentué */
        .link-text {
            color: #111827;
            text-decoration: underline;
            font-weight: bold;
        }
        .font-bold {
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="pdf-header">
        <h1 class="pdf-title">Repertoire de Contacts</h1>
        <div class="pdf-subtitle">Généré le {{ date('d/m/Y H:i') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 20%;">Nom & Prénom</th>
                <th style="width: 18%;">Email</th>
                <th style="width: 15%;">Groupe</th>
                <th style="width: 20%;">Adresse</th>
                <th style="width: 15%;">Téléphone</th>
                <th style="width: 12%;">Date de naissance</th>
            </tr>
        </thead>
        <tbody>
            @forelse($contacts as $contact)
                <tr>
                    <td class="font-bold">
                        {{ $contact->name }} {{ $contact->surname }}
                        @if($contact->favoris)
                            <span style="color: #d97706;">★</span>
                        @endif
                    </td>
                    <td>
                        <span class="link-text">{{ $contact->email ?? '-' }}</span>
                    </td>
                    <td>
                        <span class="pill pill-group">
                            {{ ucfirst($contact->group ?? 'Général') }}
                        </span>
                    </td>
                    <td>
                        @if($contact->adress)
                            <span class="pill pill-address">📍 {{ $contact->adress }}</span>
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $contact->phone ?? '-' }}</td>
                    <td>{{ $contact->Birthday ? $contact->Birthday->format('d/m/Y') : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px; color: #9ca3af;">
                        Aucun contact enregistré.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>