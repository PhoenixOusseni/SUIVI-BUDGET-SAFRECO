<!DOCTYPE html>
<html lang="fr">

<head>
    @include('admin.partials.meta')
    @yield('title')
    <title>ORDRE PHARMACIEN</title>
    @yield('style')
    @include('admin.partials.style')
    <style>
        .inset-0 {
            z-index: 999999999 !important;
        }
    </style>

<body style="height: 90vh;">
    <div class="container-fluid mt-1">
        <div style="border-bottom: 1px solid black;">
            <div class="d-flex col-md-12">
                <div class="col-12">
                    <img src="{{ asset('assets/img/logo_banner.png') }}" alt="">
                </div>
            </div>
            <div>
                <p>Tel : 25 36 50 81 / 70 24 04 65</p>
            </div>
        </div>
        <div class="d-flex justify-content-end">
            <h5>Ouagadougou, le {{ date('d-m-Y') }}</h5>
        </div>
        <section class="mt-4 mb-4">
            <div class="row m-1">
                <div class="col-6 pt-2">
                    <h3 class="mb-3">Quittance N° : {{ $finds->code }}</h3>
                </div>
            </div>
            <h4>Doit : {{ $finds->User->nom }} {{ $finds->User->prenom }}</h4>
        </section>
        <section>
            <table class="table table-bordered">
                <tr style="background: rgb(202, 200, 200)">
                    <th class="text-center">Désignation</th>
                    <th class="text-center">Période</th>
                    <th class="text-center">Montant</th>
                </tr>
                <tr>
                    <td>
                        <p>Pour paiement de la cotisation à l'ordre des pharmaciens</p>
                    </td>
                    <td class="text-center">{{ $finds->periode }} {{ $finds->Annee->annee }}</td>
                    <td class="text-center">{{ number_format($finds->montant, 0, ',', ' ') }}</td>
                </tr>
            </table>
            <div class="d-flex justify-content-between m-1 bg-success p-2">
                <div>
                    <h4 class="text-light"><strong>TOTAL</strong></h4>
                </div>
                <div>
                    <h4 class="text-light"><strong>{{ number_format($finds->montant, 0, ',', ' ') }} FCFA</strong></h4>
                </div>
            </div>

            @php
                $fmt = new NumberFormatter('fr', NumberFormatter::SPELLOUT);
            @endphp

            <h5>Arreté la présente quitance à la somme de : <strong
                    class="text-uppercase">{{ ucfirst($fmt->format($finds->montant)) }}</strong>
                ({{ number_format($finds->montant, 0, ',', ' ') }}) FRANCS
                CFA</h5>
        </section>
        <section class="mt-3" style="margin-bottom: 20px;">
            <div class="">
                <div style="margin-bottom: 90px;">
                    <h5><strong>Signature Agence</strong></h5>
                </div>
                <div class="mt-5">
                    <h5><strong>{{ $finds->User->nom }} {{ $finds->User->prenom }}</strong></h5>
                </div>
            </div>
        </section>
    </div>
    <script>
        window.print();
    </script>
</body>

</html>
