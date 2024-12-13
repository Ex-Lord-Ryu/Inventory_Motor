@extends('layouts.app')

@section('content')
    @push('style')
        <style>
            .card {
                border-radius: 15px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }

            .card-header {
                border-radius: 15px 15px 0 0;
            }

            .btn-primary {
                transition: all 0.3s;
            }

            .btn-primary:hover {
                transform: translateY(-2px);
            }

            .form-control:focus {
                box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            }

            .main-content {
                padding-top: 20px;
            }

            #yearSelect {
                width: 200px;
            }

            #stock-chart,
            #order-chart,
            #sold-chart {
                height: 350px;
            }
        </style>
    @endpush

    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1><i class="fas fa-chart-line mr-2"></i>{{ __('Dashboard') }}</h1>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">{{ __('Dashboard') }}</h4>
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="yearSelect">Year</label>
                                    <select id="yearSelect" class="form-control">
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button id="updateCharts" class="btn btn-primary btn-block">Update Charts</button>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div id="stock-chart"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div id="order-chart"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div id="sold-chart"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        let stockChart, orderChart, soldChart;

        function createChart(elementId, title, data, categories) {
            var options = {
                series: [{
                        name: 'Motors',
                        data: data.motors || []
                    },
                    {
                        name: 'Spare Parts',
                        data: data.spareParts || []
                    }
                ],
                chart: {
                    height: 350,
                    type: 'bar',
                    zoom: {
                        enabled: false
                    },
                    toolbar: {
                        show: false
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                title: {
                    text: title,
                    align: 'center'
                },
                grid: {
                    row: {
                        colors: ['#f3f3f3', 'transparent'],
                        opacity: 0.5
                    },
                },
                xaxis: {
                    categories: categories,
                },
                yaxis: {
                    title: {
                        text: 'Count'
                    }
                },
                legend: {
                    position: 'top'
                },
                colors: ['#008FFB', '#00E396']
            };

            return new ApexCharts(document.querySelector(elementId), options);
        }

        function updateCharts(data) {
            stockChart.updateOptions({
                series: [{
                        name: 'Motors',
                        data: data.stock.motors
                    },
                    {
                        name: 'Spare Parts',
                        data: data.stock.spareParts
                    }
                ],
                xaxis: {
                    categories: data.months
                }
            });
            orderChart.updateOptions({
                series: [{
                        name: 'Motors',
                        data: data.order.motors
                    },
                    {
                        name: 'Spare Parts',
                        data: data.order.spareParts
                    }
                ],
                xaxis: {
                    categories: data.months
                }
            });
            soldChart.updateOptions({
                series: [{
                        name: 'Motors',
                        data: data.sold.motors
                    },
                    {
                        name: 'Spare Parts',
                        data: data.sold.spareParts
                    }
                ],
                xaxis: {
                    categories: data.months
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM fully loaded');
            const yearSelect = document.getElementById('yearSelect');
            const updateButton = document.getElementById('updateCharts');

            // Populate year select
            const currentYear = new Date().getFullYear();
            for (let year = currentYear; year >= currentYear - 10; year--) {
                const option = document.createElement('option');
                option.value = year;
                option.textContent = year;
                yearSelect.appendChild(option);
            }

            // Initial chart creation
            fetchDataAndUpdateCharts();

            // Update charts on button click
            updateButton.addEventListener('click', fetchDataAndUpdateCharts);

            function fetchDataAndUpdateCharts() {
                const selectedYear = yearSelect.value;
                console.log('Fetching data for year:', selectedYear);
                axios.get('{{ route('get.data') }}', {
                    params: { year: selectedYear }
                }).then(function(response) {
                    console.log('Data received:', response.data);
                    const data = response.data;
                    if (!stockChart) {
                        console.log('Creating new charts');
                        stockChart = createChart("#stock-chart", "Stock", data.stock, data.months);
                        orderChart = createChart("#order-chart", "Orders", data.order, data.months);
                        soldChart = createChart("#sold-chart", "Sold", data.sold, data.months);

                        stockChart.render();
                        orderChart.render();
                        soldChart.render();
                    } else {
                        console.log('Updating existing charts');
                        updateCharts(data);
                    }
                }).catch(function(error) {
                    console.error('Error fetching data:', error);
                });
            }
        });
    </script>
@endpush
