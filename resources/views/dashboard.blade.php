<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="d-md-flex align-items-center mb-3 mx-2">
                        <div class="mb-md-0 mb-3">
                            <h3 class="font-weight-bold mb-0">Panel de control</h3>
                            <p class="mb-0">Vista general sobre los registros financieros de la tienda</p>
                        </div>
                        <!-- <button type="button"
                            class="btn btn-sm btn-white btn-icon d-flex align-items-center mb-0 ms-md-auto mb-sm-0 mb-2 me-2">
                            <span class="btn-inner--icon">
                                <span class="p-1 bg-success rounded-circle d-flex ms-auto me-2">
                                    <span class="visually-hidden">Nuevo</span>
                                </span>
                            </span>
                            <span class="btn-inner--text">Mensajes</span>
                        </button>
                        <button type="button" class="btn btn-sm btn-dark btn-icon d-flex align-items-center mb-0">
                            <span class="btn-inner--icon">
                                <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="d-block me-2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                </svg>
                            </span>
                            <span class="btn-inner--text">Sincronizar</span>
                        </button>
                        -->
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-xl-3 col-sm-6 mb-xl-0">
                    <div class="card border shadow-xs">
                        <div class="card-body text-start p-3 w-100">
                            <div
                                class="icon icon-shape icon-sm bg-dark text-white text-center border-radius-sm d-flex align-items-center justify-content-center mb-3">
                                <svg height="16" width="16" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M4.5 3.75a3 3 0 00-3 3v.75h21v-.75a3 3 0 00-3-3h-15z" />
                                    <path fill-rule="evenodd"
                                        d="M22.5 9.75h-21v7.5a3 3 0 003 3h15a3 3 0 003-3v-7.5zm-18 3.75a.75.75 0 01.75-.75h6a.75.75 0 010 1.5h-6a.75.75 0 01-.75-.75zm.75 2.25a.75.75 0 000 1.5h3a.75.75 0 000-1.5h-3z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="w-100">
                                        <p class="text-sm text-secondary mb-1">Balance total</p>
                                        <h4 class="mb-0 font-weight-bold">${{ $balance < 0 ? 0 : number_format($balance, 2, ',', '.') }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-xl-0">
                    <div class="card border shadow-xs">
                        <div class="card-body text-start p-3 w-100">
                            <div
                                class="icon icon-shape icon-sm bg-dark text-white text-center border-radius-sm d-flex align-items-center justify-content-center mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="#fff" viewBox="0 0 256 256">
                                    <path id="Path" class="color-foreground" d="M240,56v64a8,8,0,0,1-16,0V75.31l-82.34,82.35a8,8,0,0,1-11.32,0L96,123.31,29.66,189.66a8,8,0,0,1-11.32-11.32l72-72a8,8,0,0,1,11.32,0L136,140.69,212.69,64H168a8,8,0,0,1,0-16h64A8,8,0,0,1,240,56Z"></path>
                                    <path id="Path" class="color-background" d="M240,56v64a8,8,0,0,1-16,0V75.31l-82.34,82.35a8,8,0,0,1-11.32,0L96,123.31,29.66,189.66a8,8,0,0,1-11.32-11.32l72-72a8,8,0,0,1,11.32,0L136,140.69,212.69,64H168a8,8,0,0,1,0-16h64A8,8,0,0,1,240,56Z"></path>
                                </svg>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="w-100">
                                        <p class="text-sm text-secondary mb-1">Ingresos</p>
                                        <h4 class="mb-0 font-weight-bold">${{ number_format($ingresos, 2, ',', '.') }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>  
                </div>
                <div class="col-xl-3 col-sm-6 mb-xl-0">
                    <div class="card border shadow-xs">
                        <div class="card-body text-start p-3 w-100">
                            <div
                                class="icon icon-shape icon-sm bg-dark text-white text-center border-radius-sm d-flex align-items-center justify-content-center mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="#fff" viewBox="0 0 256 256">
                                    <path id="Path" class="color-foreground" d="M240,128v64a8,8,0,0,1-8,8H168a8,8,0,0,1,0-16h44.69L136,107.31l-34.34,34.35a8,8,0,0,1-11.32,0l-72-72A8,8,0,0,1,29.66,58.34L96,124.69l34.34-34.35a8,8,0,0,1,11.32,0L224,172.69V128a8,8,0,0,1,16,0Z"></path>
                                    <path id="Path" class="color-background" d="M240,128v64a8,8,0,0,1-8,8H168a8,8,0,0,1,0-16h44.69L136,107.31l-34.34,34.35a8,8,0,0,1-11.32,0l-72-72A8,8,0,0,1,29.66,58.34L96,124.69l34.34-34.35a8,8,0,0,1,11.32,0L224,172.69V128a8,8,0,0,1,16,0Z"></path>
                                </svg>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="w-100">
                                        <p class="text-sm text-secondary mb-1">Movimientos</p>
                                        <h4 class="mb-0 font-weight-bold">${{ number_format($gastos, 2, ',', '.') }}</h4>
                                        <!-- <div class="d-flex align-items-center">
                                            <span class="text-sm text-success font-weight-bolder">
                                                <i class="fa fa-chevron-up text-xs me-1"></i>22%
                                            </span>
                                            <span class="text-sm ms-1">from $369.30</span>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6">
                    <div class="card border shadow-xs">
                        <div class="card-body text-start p-3 w-100">
                            <div
                                class="icon icon-shape icon-sm bg-dark text-white text-center border-radius-sm d-flex align-items-center justify-content-center mb-3">
                                <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M5.25 2.25a3 3 0 00-3 3v4.318a3 3 0 00.879 2.121l9.58 9.581c.92.92 2.39 1.186 3.548.428a18.849 18.849 0 005.441-5.44c.758-1.16.492-2.629-.428-3.548l-9.58-9.581a3 3 0 00-2.122-.879H5.25zM6.375 7.5a1.125 1.125 0 100-2.25 1.125 1.125 0 000 2.25z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="w-100">
                                        <p class="text-sm text-secondary mb-1">Cant. Transacciones</p>
                                        <h4 class="mb-0 font-weight-bold">{{ $cantTransacciones }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row my-4">
                <div class="col-lg-12">
                    <div class="card shadow-xs border">
                        <div class="card-header pb-0">
                            <div class="d-sm-flex align-items-center mb-3">
                                <div>
                                    <h6 class="font-weight-semibold text-lg mb-0">Transacciones diarias</h6>
                                    <p class="text-sm mb-sm-0 mb-2">Aquí tienes detalles sobre entre gastos y ventas por día.</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="chart mt-n6">
                                <canvas id="chart-line" class="chart-canvas" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-md-0 mb-4">
                    <div class="card shadow-xs border h-100">
                        <div class="card-header pb-0">
                            <h6 class="font-weight-semibold text-lg mb-0">Saldos a lo largo del tiempo</h6>
                            <p class="text-sm">Aquí tienes detalles sobre el saldo.</p>
                        </div>
                        <div class="card-body py-3">
                            <div class="chart mb-2">
                                <canvas id="chart-bars" class="chart-canvas" height="240"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-6">
                    <div class="card shadow-xs border">
                        <div class="card-header border-bottom pb-0">
                            <div class="d-sm-flex align-items-center mb-3">
                                <div>
                                    <h6 class="font-weight-semibold text-lg mb-0">Últimas transacciones</h6>
                                    <p class="text-sm mb-sm-0 mb-2">Aquí tienes detalles sobre las últimas transacciones</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 py-0">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center justify-content-center mb-0">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7">
                                                ID</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">
                                                Descripcion</th>
                                            <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Fecha
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ultimosRegistros as $transaction)
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2">
                                                        <div class="my-auto">
                                                            <h6 class="mb-0 text-sm">{{ $transaction->ID }}</h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-normal mb-0">{{ $transaction->Descripcion }}</p>
                                                </td>
                                                <td>
                                                    <span class="text-sm font-weight-normal">{{ $transaction->Fecha_Venta ?? $transaction->Fecha_Gasto }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            /**
             * ----------------- Tabla de Saldos a lo largo del tiempo -----------------
             */
            var balancePorDiaCompleto = @json($balancePorDiaCompleto);
            console.log(balancePorDiaCompleto);
            var ctx = document.getElementById("chart-bars").getContext("2d");

            new Chart(ctx, {
                type: "bar",
                data: {
                    labels: Object.keys(balancePorDiaCompleto),
                    datasets: [{
                            label: "Sales",
                            tension: 0.4,
                            borderWidth: 0,
                            borderSkipped: false,
                            backgroundColor: "#2ca8ff",
                            data: Object.values(balancePorDiaCompleto),
                            maxBarThickness: 6
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false,
                        },
                        tooltip: {
                            backgroundColor: '#fff',
                            titleColor: '#1e293b',
                            bodyColor: '#1e293b',
                            borderColor: '#e9ecef',
                            borderWidth: 1,
                            usePointStyle: true
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                    scales: {
                        y: {
                            stacked: true,
                            grid: {
                                drawBorder: false,
                                display: true,
                                drawOnChartArea: true,
                                drawTicks: false,
                                borderDash: [4, 4],
                            },
                            ticks: {
                                callback: function(value, index, ticks) {
                                    return parseInt(value).toLocaleString() + ' COP';
                                },
                                beginAtZero: true,
                                padding: 10,
                                font: {
                                    size: 12,
                                    family: "Noto Sans",
                                    style: 'normal',
                                    lineHeight: 2
                                },
                                color: "#64748B"
                            },
                        },
                        x: {
                            stacked: true,
                            grid: {
                                drawBorder: false,
                                display: false,
                                drawOnChartArea: false,
                                drawTicks: false
                            },
                            ticks: {
                                font: {
                                    size: 12,
                                    family: "Noto Sans",
                                    style: 'normal',
                                    lineHeight: 2
                                },
                                color: "#64748B"
                            },
                        },
                    },
                },
            });

            /**
             * ----------------- Tabla de Vista General -----------------
             */
            var ventasPorDiaFormatted = @json($ventasPorDiaCompleto);
            var gastosPorDiaFormatted = @json($gastosPorDiaCompleto);

            var ctx2 = document.getElementById("chart-line").getContext("2d");

            var gradientStroke1 = ctx2.createLinearGradient(0, 230, 0, 50);

            gradientStroke1.addColorStop(1, 'rgba(45,168,255,0.2)');
            gradientStroke1.addColorStop(0.2, 'rgba(45,168,255,0.0)');
            gradientStroke1.addColorStop(0, 'rgba(45,168,255,0)'); //blue colors

            var gradientStroke2 = ctx2.createLinearGradient(0, 230, 0, 50);

            gradientStroke2.addColorStop(1, 'rgba(119,77,211,0.4)');
            gradientStroke2.addColorStop(0.7, 'rgba(119,77,211,0.1)');
            gradientStroke2.addColorStop(0, 'rgba(119,77,211,0)'); //purple colors

            new Chart(ctx2, {
                plugins: [{
                    beforeInit(chart) {
                        const originalFit = chart.legend.fit;
                        chart.legend.fit = function fit() {
                            originalFit.bind(chart.legend)();
                            this.height += 40;
                        }
                    },
                }],
                type: "line",
                data: {
                    labels: Object.keys(ventasPorDiaFormatted),
                    datasets: [{
                            label: "Ventas",
                            tension: 0,
                            borderWidth: 2,
                            pointRadius: 3,
                            borderColor: "#2ca8ff",
                            pointBorderColor: '#2ca8ff',
                            pointBackgroundColor: '#2ca8ff',
                            backgroundColor: gradientStroke1,
                            fill: true,
                            data: Object.values(ventasPorDiaFormatted),
                            maxBarThickness: 6

                        },
                        {
                            label: "Gastos",
                            tension: 0,
                            borderWidth: 2,
                            pointRadius: 3,
                            borderColor: "#832bf9",
                            pointBorderColor: '#832bf9',
                            pointBackgroundColor: '#832bf9',
                            backgroundColor: gradientStroke2,
                            fill: true,
                            data: Object.values(gastosPorDiaFormatted),
                            maxBarThickness: 6
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            align: 'end',
                            labels: {
                                boxWidth: 6,
                                boxHeight: 6,
                                padding: 20,
                                pointStyle: 'circle',
                                borderRadius: 50,
                                usePointStyle: true,
                                font: {
                                    weight: 400,
                                },
                            },
                        },
                        tooltip: {
                            backgroundColor: '#fff',
                            titleColor: '#1e293b',
                            bodyColor: '#1e293b',
                            borderColor: '#e9ecef',
                            borderWidth: 1,
                            pointRadius: 2,
                            usePointStyle: true,
                            boxWidth: 8,
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                    scales: {
                        y: {
                            grid: {
                                drawBorder: false,
                                display: true,
                                drawOnChartArea: true,
                                drawTicks: false,
                                borderDash: [4, 4]
                            },
                            ticks: {
                                callback: function(value, index, ticks) {
                                    return parseInt(value).toLocaleString() + ' COP';
                                },
                                display: true,
                                padding: 10,
                                color: '#b2b9bf',
                                font: {
                                    size: 12,
                                    family: "Noto Sans",
                                    style: 'normal',
                                    lineHeight: 2
                                },
                                color: "#64748B"
                            }
                        },
                        x: {
                            grid: {
                                drawBorder: false,
                                display: false,
                                drawOnChartArea: false,
                                drawTicks: false,
                                borderDash: [4, 4]
                            },
                            ticks: {
                                display: true,
                                color: '#b2b9bf',
                                padding: 20,
                                font: {
                                    size: 12,
                                    family: "Noto Sans",
                                    style: 'normal',
                                    lineHeight: 2
                                },
                                color: "#64748B"
                            }
                        },
                    },
                },
            });
        })
    </script>
</x-app-layout>
