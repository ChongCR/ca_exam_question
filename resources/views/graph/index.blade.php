<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Graph') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6 text-gray-900">

                    <div class="flex justify-between">
                        <p>
                            {{ __('Your Graph Here') }}
                        </p>

                        <x-button onclick="downloadSampleData()">
                            Download Sample Data
                        </x-button>
                    </div>
                    <div class="flex">
                        <p>{{ __('Equity Over Graph Line Chart')}}</p>
                    </div>
                    <canvas id="equityChart"></canvas>
                </div>


                <div class="grid grid-cols-2 gap-4 p-6">
                    <div class="shadow-xl border p-4 rounded-lg">
                        <h1>Annual Return</h1>
                        <p>{{ $metrics['annual_return'] }}%</p>
                    </div>
                    <div class="shadow-xl border p-4 rounded-lg">
                        <h1>Sharpe Ratio</h1>
                        <p>{{ $metrics['sharpe_ratio'] }}</p>
                    </div>
                    <div class="shadow-xl border p-4 rounded-lg">
                        <h1>Maximum Drawdown</h1>
                        <p>{{ $metrics['max_drawdown'] }}%</p>
                    </div>
                    <div class="shadow-xl border p-4 rounded-lg">
                        <h1>Calmar Ratio</h1>
                        <p>{{ $metrics['calmar_ratio'] }}</p>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        function downloadSampleData() {
            window.open('{{ asset("sample_data.csv") }}', "_blank")
        }

        const dates = @json(array_column($data, 'date'));
        const equity = @json(array_column($data, 'equity'));
        
        const ctx = document.getElementById('equityChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Equity',
                    data: equity,
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    pointRadius: 0,
                    tension: 0.1,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Date'
                        },
                        ticks: {
                            maxTicksLimit: 20
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Equity Value'
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>