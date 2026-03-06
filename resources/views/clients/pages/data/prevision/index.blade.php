@extends('clients.layouts.master')

@section('content')
<div class="container-fluid px-3 pb-4">

    <div class="page-hero">
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-2">
            <div>
                <p class="hero-title"><i class="fas fa-chart-bar me-2"></i>Liste des Prévisions</p>
                <p class="hero-sub">Visualisez les prévisions budgétaires par ligne et par mois</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('data.prevision.saisi_prevision') }}" class="btn-primary-custom"><i class="fas fa-plus"></i> Saisir</a>
                <button type="button" class="btn-secondary-custom" data-bs-toggle="modal" data-bs-target="#importModal"><i class="fas fa-file-import"></i> Importer</button>
                <a href="{{ route('previsions.export', ['year' => request('year', date('Y')), 'ligne_budget_id' => request('ligne_budget_id')]) }}" class="btn-export"><i class="fas fa-file-export"></i> Exporter</a>
                <a href="{{ route('previsions.print', ['year' => request('year', date('Y')), 'ligne_budget_id' => request('ligne_budget_id')]) }}" class="btn-print" target="_blank"><i class="fas fa-print"></i> Imprimer</a>
            </div>
        </div>
    </div>

    {{-- Toolbar Filtres --}}
    <div class="toolbar-card mb-4">
        <form method="GET" action="{{ route('gestion_previsions.index') }}" class="d-flex gap-2 flex-wrap align-items-center">
            @php
                $currentYear = request('year', date('Y'));
                $start = date('Y') - 3;
                $end = date('Y') + 3;
            @endphp
            <select name="year" class="form-select" style="width:auto;">
                @for ($y = $start; $y <= $end; $y++)
                    <option value="{{ $y }}" {{ (int) $currentYear === $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
            <select name="ligne_budget_id" class="form-select" style="width:auto; min-width:200px;">
                <option value="">Toutes les lignes budgétaires</option>
                @foreach ($lignesBudgets as $cb)
                    <option value="{{ $cb->id }}" {{ request('ligne_budget_id') == $cb->id ? 'selected' : '' }}>
                        {{ $cb->code }}{{ isset($cb->intitule) ? ' - ' . $cb->intitule : '' }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="btn-primary-custom"><i class="fas fa-filter"></i> Filtrer</button>
        </form>
    </div>

    {{-- Table --}}
    <div class="table-card">
        <div class="table-card-header">
            <div class="tch-icon"><i class="fas fa-chart-bar"></i></div>
            <div>
                <p class="tch-title">Prévisions — {{ $currentYear }}</p>
                <p class="tch-sub mb-0">{{ count($groupedPrevisions) }} ligne(s)</p>
            </div>
        </div>
        @php
            $months = [1=>'Janvier',2=>'Février',3=>'Mars',4=>'Avril',5=>'Mai',6=>'Juin',
                       7=>'Juillet',8=>'Août',9=>'Septembre',10=>'Octobre',11=>'Novembre',12=>'Décembre'];
            $colTotals = array_fill(1, 12, 0.0);
            $grandTotal = 0.0;
        @endphp
        <div class="table-responsive" style="max-height:70vh; overflow:auto;">
            <table class="table table-bordered table-sm table-exec align-middle mb-0" style="min-width:1600px;">
                <thead style="position:sticky; top:0; z-index:5;">
                    <tr>
                        <th style="width:140px;">CODE</th>
                        <th style="min-width:260px;">Libellés</th>
                        @foreach ($months as $m)
                            <th class="text-end" style="width:120px;">{{ $m }}</th>
                        @endforeach
                        <th class="text-center" style="width:140px; background:rgba(241,180,110,.5);">Total</th>
                        <th style="width:90px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($groupedPrevisions as $ligneId => $prevs)
                        @php
                            $prevision = $prevs->first();
                            $ligne = $lignesMap->get($ligneId);
                            $rowMonths = array_fill(1, 12, 0.0);
                            if ($prevision && $prevision->relationLoaded('months')) {
                                foreach ($prevision->months as $pm) { $rowMonths[(int)$pm->month] = (float)$pm->amount; }
                            } elseif ($prevision) {
                                foreach ($prevision->months()->get() as $pm) { $rowMonths[(int)$pm->month] = (float)$pm->amount; }
                            }
                            $rowTotal = array_sum($rowMonths);
                            foreach ($rowMonths as $k => $v) { $colTotals[$k] += $v; }
                            $grandTotal += $rowTotal;
                        @endphp
                        <tr>
                            <td><span class="status-badge blue">{{ $ligne ? $ligne->code : '—' }}</span></td>
                            <td>{{ $ligne ? ($ligne->intitule ?? ($ligne->name ?? '')) : ($prevision->description ?? '') }}</td>
                            @foreach ($rowMonths as $amt)
                                <td class="text-end">{{ $amt ? number_format($amt, 0, ',', ' ') : '' }}</td>
                            @endforeach
                            <td class="text-center" style="background:rgba(241,180,110,.25);">
                                <strong>{{ $rowTotal ? number_format($rowTotal, 0, ',', ' ') : '' }}</strong>
                            </td>
                            <td>
                                @if($prevision)
                                <div class="action-group">
                                    <form action="{{ route('gestion_previsions.destroy', $prevision->id) }}" method="POST" style="display:inline-block;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-danger-custom" onclick="return confirm('Supprimer cette prévision ?')"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ 2 + count($months) + 2 }}" class="text-center py-3 text-muted">Aucune prévision enregistrée pour {{ $currentYear }}.</td>
                        </tr>
                    @endforelse
                    @if(count($groupedPrevisions) > 0)
                    <tr style="background:#f1f5f9; font-weight:700; border-top:2px solid #cbd5e1;">
                        <td colspan="2" class="text-end" style="font-size:.8rem; letter-spacing:.3px;">TOTAL</td>
                        @foreach ($colTotals as $ct)
                            <td class="text-end">{{ $ct ? number_format($ct, 0, ',', ' ') : '' }}</td>
                        @endforeach
                        <td class="text-center" style="background:rgba(241,180,110,.35);">
                            {{ $grandTotal ? number_format($grandTotal, 0, ',', ' ') : '' }}
                        </td>
                        <td></td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Import Modal --}}
<div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:14px; border:none; box-shadow:0 8px 32px rgba(0,61,130,.15);">
            <div class="modal-header modal-header-blue">
                <h5 class="modal-title"><i class="fas fa-file-import me-2"></i>Importer des prévisions</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('previsions.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="file" class="form-label">Sélectionner un fichier Excel</label>
                        <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file" accept=".xlsx,.xls,.csv" required>
                        @error('file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="info-banner">
                        <strong>Format attendu :</strong>
                        <ul class="mb-0 mt-1">
                            <li>Colonnes requises : <code>code</code>, <code>annee</code></li>
                            <li>Mois : <code>janvier</code>, <code>fevrier</code>, <code>mars</code>, etc.</li>
                            <li>Le code doit correspondre à une ligne budgétaire existante</li>
                        </ul>
                    </div>
                </div>
                <div class="d-flex gap-2 px-4 pb-4">
                    <button type="submit" class="btn-primary-custom"><i class="fas fa-upload"></i> Importer</button>
                    <button type="button" class="btn-secondary-custom" data-bs-dismiss="modal"><i class="fas fa-times"></i> Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if (session('import_messages'))
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">Détails de l'import</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                <ul class="mb-0">
                    @foreach (session('import_messages') as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif
@endsection
