<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport d'Affectation des Agents - USSEIN</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 11px; color: #333; margin: 10px; }
        .header { text-align: center; margin-bottom: 25px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .title { font-size: 18px; font-weight: bold; text-transform: uppercase; margin: 0; color: #111; }
        .subtitle { font-size: 11px; color: #555; margin-top: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background-color: #f5f5f5; color: #000; font-weight: bold; text-transform: uppercase; font-size: 10px; border: 1px solid #ddd; padding: 8px; }
        td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        tr:nth-child(even) { background-color: #fafafa; }
        .text-end { text-align: right; }
    </style>
</head>
<body>

    <div class="header">
        <div class="title">Rapport d'affectation des Agents - USSEIN</div>
        <div class="subtitle">
            Structure : {{ ucfirst($type ?? 'Toutes') }}
            @if($date_debut && $date_fin)
                — Période : du {{ \Carbon\Carbon::parse($date_debut)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($date_fin)->format('d/m/Y') }}
            @endif
            — Généré le {{ now()->format('d/m/Y') }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 25%;">Agent</th>
                <th style="width: 15%;">Matricule Solde</th>
                <th style="width: 25%;">Lieu d'affectation</th>
                <th style="width: 15%;">Type d'agent</th>
                <th style="width: 8%;">Sexe</th>
                <th style="width: 12%;" class="text-end">Prise de service</th>
            </tr>
        </thead>
        <tbody>
            @forelse($agents as $agent)
                <tr>
                    <td><strong>{{ $agent->prenom }} {{ $agent->nom }}</strong></td>
                    <td>{{ $agent->matricule_solde ?? '-' }}</td>
                    <td>{{ $agent->lieu_affectation ?? '-' }}</td>
                    <td>{{ $agent->type_agent ?? '-' }}</td>
                    <td>{{ $agent->sexe ?? '-' }}</td>
                    <td class="text-end">
                        {{ $agent->date_prise_service ? \Carbon\Carbon::parse($agent->date_prise_service)->format('d/m/Y') : '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px; color: #666;">
                        Aucun agent trouvé pour ce critère.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
