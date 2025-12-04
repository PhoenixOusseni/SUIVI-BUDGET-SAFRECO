@extends('clients.layouts.master')

@section('content')
    <div class="container">
        <h2 class="page-title">TABLEAU DE BORD</h2>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card text-white bg-warning h-100">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <h5 class="card-title">Suivi Budgétaire</h5>
                        <canvas id="budgetsChart" width="150" height="90"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card text-white bg-success h-100">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <h5 class="card-title">Suivi de la trésorerie</h5>
                        <canvas id="expensesChart" width="150" height="90"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('expensesChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut', // ou 'bar', 'pie', etc.
                data: {
                    labels: ['Dépenses', 'Reste'],
                    datasets: [{
                        data: [850000, 150000], // Exemple : 850000 dépensé, 150000 reste (total 1M)
                        backgroundColor: ['#fff', 'rgba(255,255,255,0.4)'],
                        borderColor: ['#fff', '#fff'],
                        borderWidth: 1
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            labels: {
                                color: 'white'
                            }
                        }
                    }
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('budgetsChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar', // ou 'bar', 'pie', etc.
                data: {
                    labels: ['Budget', 'Reste'],
                    datasets: [{
                        data: [850000, 150000], // Exemple : 850000 dépensé, 150000 reste (total 1M)
                        backgroundColor: ['#fff', 'rgba(255,255,255,0.4)'],
                        borderColor: ['#fff', '#fff'],
                        borderWidth: 1
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            labels: {
                                color: 'white'
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection
