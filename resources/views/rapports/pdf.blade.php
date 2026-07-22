<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{{ $titre }}</title>
    <style>
        * { font-family: DejaVu Sans, sans-serif; }
        body { font-size: 10px; color: #1b2233; margin: 0; }

        .entete { border-bottom: 3px solid #e94560; padding-bottom: 10px; margin-bottom: 16px; }
        .entete .univ { font-size: 8.5px; color: #6e7787; text-transform: uppercase; letter-spacing: .6px; }
        .entete h1 { font-size: 17px; margin: 5px 0 3px; color: #1a1a2e; }
        .entete .meta { font-size: 9px; color: #6e7787; }

        h2.section { font-size: 12px; color: #1a1a2e; margin: 18px 0 8px;
                     border-left: 4px solid #e94560; padding-left: 8px; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        th { background: #1a1a2e; color: #fff; padding: 6px; text-align: left; font-size: 9px; }
        td { padding: 5px 6px; border-bottom: 1px solid #e2e7f0; font-size: 9px; }
        tr:nth-child(even) td { background: #f7f8fb; }

        .stats { width: 100%; margin-bottom: 12px; }
        .stats td { border: none; text-align: center; padding: 8px 4px; background: #f4f6fa; }
        .stats .nb { font-size: 17px; font-weight: bold; display: block; }
        .stats .lb { font-size: 8px; color: #6e7787; text-transform: uppercase; }

        .vert { color: #1e8a5a; } .rouge { color: #c0392b; }
        .orange { color: #d68910; } .gris { color: #5a6478; }

        .badge { padding: 2px 6px; border-radius: 8px; font-size: 8px; font-weight: bold; }
        .b-ok { background: #e3f6ec; color: #1e8a5a; }
        .b-no { background: #fdecef; color: #c0392b; }
        .b-wt { background: #fdf3e0; color: #d68910; }

        .vide { text-align: center; color: #8892a4; padding: 14px; font-style: italic; }
        .pied { position: fixed; bottom: 0; left: 0; right: 0;
                font-size: 8px; color: #8892a4; border-top: 1px solid #e2e7f0; padding-top: 5px; }
    </style>
</head>
<body>

<div class="entete">
    <div class="univ">Université du Sine Saloum El-Hâdj Ibrahima NIASS</div>
    <h1>{{ $titre }}</h1>
    <div class="meta">
        Période :
        @if($date_debut && $date_fin)
            du {{ \Carbon\Carbon::parse($date_debut)->format('d/m/Y') }}
            au {{ \Carbon\Carbon::parse($date_fin)->format('d/m/Y') }}
        @else
            toutes périodes
        @endif
        @if($affectation) &nbsp;•&nbsp; Affectation : {{ $affectation }} @endif
        &nbsp;•&nbsp; Généré le {{ $genereLe->format('d/m/Y à H:i') }}
    </div>
</div>

{{-- ================= AGENTS ================= --}}
@if(in_array($type, ['agents', 'complet']))
    <h2 class="section">Agents inscrits ({{ $agents->count() }})</h2>
    <table>
        <thead>
            <tr>
                <th>Matricule</th><th>Nom et prénom</th><th>Sexe</th><th>Type</th>
                <th>Affectation</th><th>Prise de service</th><th>Ancienneté</th><th>Solde</th>
            </tr>
        </thead>
        <tbody>
        @forelse($agents as $a)
            <tr>
                <td>{{ $a->matricule_solde }}</td>
                <td>{{ $a->nom }} {{ $a->prenom }}</td>
                <td>{{ ucfirst($a->sexe) }}</td>
                <td>{{ ucfirst($a->type_agent) }}</td>
                <td>{{ $a->lieu_affectation }}</td>
                <td>{{ $a->date_prise_service ? $a->date_prise_service->format('d/m/Y') : '-' }}</td>
                <td>{{ $a->anneesService() }} an(s)</td>
                <td>{{ $a->soldeDisponible() }} j</td>
            </tr>
        @empty
            <tr><td colspan="8" class="vide">Aucun agent sur cette période.</td></tr>
        @endforelse
        </tbody>
    </table>
@endif

{{-- ================= CONGÉS ================= --}}
@if(in_array($type, ['conges', 'complet']))
    <h2 class="section">Demandes de congé</h2>

    <table class="stats">
        <tr>
            <td><span class="nb gris">{{ $statsConges['recues'] ?? 0 }}</span><span class="lb">Reçues</span></td>
            <td><span class="nb vert">{{ $statsConges['approuvees'] ?? 0 }}</span><span class="lb">Approuvées</span></td>
            <td><span class="nb rouge">{{ $statsConges['refusees'] ?? 0 }}</span><span class="lb">Refusées</span></td>
            <td><span class="nb orange">{{ $statsConges['attente'] ?? 0 }}</span><span class="lb">En attente</span></td>
            <td><span class="nb gris">{{ $statsConges['jours'] ?? 0 }}</span><span class="lb">Jours accordés</span></td>
        </tr>
    </table>

    <table>
        <thead>
            <tr><th>Agent</th><th>Type</th><th>Jours</th><th>Cessation</th><th>Demandée le</th><th>Statut</th></tr>
        </thead>
        <tbody>
        @forelse($conges as $c)
            <tr>
                <td>{{ $c->agent ? $c->agent->nom.' '.$c->agent->prenom : '—' }}</td>
                <td>{{ $c->type_conge }}</td>
                <td>{{ $c->jours_a_prendre }}</td>
                <td>{{ $c->date_cessation ? \Carbon\Carbon::parse($c->date_cessation)->format('d/m/Y') : '-' }}</td>
                <td>{{ $c->created_at?->format('d/m/Y') }}</td>
                <td>
                    @if(in_array($c->statut, ['Approuvé','approuve']))
                        <span class="badge b-ok">Approuvé</span>
                    @elseif(in_array($c->statut, ['Refusé','refuse']))
                        <span class="badge b-no">Refusé</span>
                    @else
                        <span class="badge b-wt">En attente</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="vide">Aucune demande de congé sur cette période.</td></tr>
        @endforelse
        </tbody>
    </table>
@endif

{{-- ================= ABSENCES ================= --}}
@if(in_array($type, ['absences', 'complet']))
    <h2 class="section">Demandes d'absence</h2>

    <table class="stats">
        <tr>
            <td><span class="nb gris">{{ $statsAbsences['recues'] ?? 0 }}</span><span class="lb">Reçues</span></td>
            <td><span class="nb vert">{{ $statsAbsences['approuvees'] ?? 0 }}</span><span class="lb">Approuvées</span></td>
            <td><span class="nb rouge">{{ $statsAbsences['refusees'] ?? 0 }}</span><span class="lb">Refusées</span></td>
            <td><span class="nb orange">{{ $statsAbsences['attente'] ?? 0 }}</span><span class="lb">En attente</span></td>
            <td><span class="nb gris">{{ $statsAbsences['jours'] ?? 0 }}</span><span class="lb">Jours accordés</span></td>
        </tr>
    </table>

    <table>
        <thead>
            <tr><th>Agent</th><th>Motif</th><th>Jours</th><th>Début</th><th>Fin</th><th>Demandée le</th><th>Statut</th></tr>
        </thead>
        <tbody>
        @forelse($absences as $a)
            <tr>
                <td>{{ $a->agent ? $a->agent->nom.' '.$a->agent->prenom : '—' }}</td>
                <td>{{ ucfirst(str_replace('_',' ', $a->motif)) }}</td>
                <td>{{ $a->nombre_jours }}</td>
                <td>{{ $a->date_debut ? $a->date_debut->format('d/m/Y') : '-' }}</td>
                <td>{{ $a->date_fin ? $a->date_fin->format('d/m/Y') : '-' }}</td>
                <td>{{ $a->created_at?->format('d/m/Y') }}</td>
                <td>
                    @if(in_array($a->statut, ['approuve','Approuvé']))
                        <span class="badge b-ok">Approuvée</span>
                    @elseif(in_array($a->statut, ['refuse','Refusé']))
                        <span class="badge b-no">Refusée</span>
                    @else
                        <span class="badge b-wt">En attente</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="vide">Aucune demande d'absence sur cette période.</td></tr>
        @endforelse
        </tbody>
    </table>
@endif

<div class="pied">
    Plateforme de gestion des congés et absences — USSEIN &nbsp;•&nbsp;
    Document généré le {{ $genereLe->format('d/m/Y à H:i') }}
</div>

</body>
</html>
