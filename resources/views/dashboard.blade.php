<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posto 2 — Limpeza Externa | Sistema de Monitoramento IoT</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { font-family: 'Inter', sans-serif; }
        @keyframes slide-up { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes toast-in { from { opacity: 0; transform: translateX(100%); } to { opacity: 1; transform: translateX(0); } }
        @keyframes toast-out { from { opacity: 1; transform: translateX(0); } to { opacity: 0; transform: translateX(100%); } }
        .animate-slide-up { animation: slide-up 0.4s ease-out forwards; opacity: 0; }
        .animate-toast-in { animation: toast-in 0.35s ease-out; }
        .animate-toast-out { animation: toast-out 0.25s ease-in forwards; }
        .kpi-value { font-size: 2rem; line-height: 1.2; font-weight: 700; letter-spacing: -0.02em; }
        .card { background: #1e293b; border: 1px solid #334155; border-radius: 12px; transition: border-color 0.2s, box-shadow 0.2s; }
        .card:hover { border-color: #475569; box-shadow: 0 4px 20px rgba(0,0,0,0.2); }
        .scrollbar-thin::-webkit-scrollbar { width: 4px; }
        .scrollbar-thin::-webkit-scrollbar-track { background: #1e293b; }
        .scrollbar-thin::-webkit-scrollbar-thumb { background: #475569; border-radius: 2px; }
        @media (max-width: 640px) { .kpi-value { font-size: 1.5rem; } }
    </style>
</head>
<body class="bg-[#0f172a] text-gray-100 antialiased min-h-screen">

    <div id="toastContainer" class="fixed top-4 right-4 z-50 flex flex-col gap-2 max-w-sm w-full pointer-events-none"></div>

    <!-- Top Bar -->
    <div class="bg-[#1e293b] border-b border-[#334155]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-14 sm:h-16">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-industry text-white text-sm"></i>
                    </div>
                    <div class="hidden sm:block">
                        <p class="text-sm font-semibold text-gray-100">Sistema de Monitoramento IoT</p>
                        <p class="text-xs text-gray-400">Linha de Produção — Processamento de Frango</p>
                    </div>
                    <div class="sm:hidden">
                        <p class="text-sm font-semibold text-gray-100">Monitoramento IoT</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="hidden sm:flex items-center gap-2 text-xs text-gray-400">
                        <span class="inline-block w-2 h-2 rounded-full bg-green-500 shadow-lg shadow-green-500/50"></span>
                        Sistema operacional
                    </div>
                    <span class="text-xs text-gray-500 font-mono" id="clock">--:--:--</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Sub Header -->
    <div class="bg-[#1e293b] border-b border-[#334155]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 sm:py-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h1 class="text-lg sm:text-xl font-semibold text-gray-100">Posto 2 — Limpeza Externa</h1>
                    <p class="text-sm text-gray-400">Sensor ultrassônico HC-SR04 &bull; Contagem de frangos na esteira</p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <button id="btnSimular" class="inline-flex items-center gap-1.5 bg-blue-600 text-white px-3.5 py-2 rounded-lg text-sm font-medium hover:bg-blue-500 transition-colors active:bg-blue-700">
                        <i class="fas fa-plus-circle text-xs"></i>
                        Simular Detecção
                    </button>
                    <button id="btnAlerta" class="inline-flex items-center gap-1.5 bg-[#334155] text-gray-300 border border-[#475569] px-3.5 py-2 rounded-lg text-sm font-medium hover:bg-[#3b4f6b] transition-colors active:bg-[#2d3f54]">
                        <i class="fas fa-exclamation-triangle text-xs"></i>
                        Gerar Alerta
                    </button>
                    <button onclick="location.reload()" class="inline-flex items-center justify-center w-9 h-9 text-gray-500 hover:text-gray-300 hover:bg-[#334155] rounded-lg transition-colors">
                        <i class="fas fa-rotate-right text-sm"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-5">

        <!-- KPI Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            <div class="card p-4 sm:p-5 animate-slide-up" style="animation-delay:0s">
                <div class="flex items-center gap-2 mb-1">
                    <i class="fas fa-drumstick-bite text-blue-400 text-sm"></i>
                    <span class="text-xs font-medium text-gray-400 uppercase tracking-wide">Total Processado</span>
                </div>
                <p id="totalChickens" class="kpi-value text-gray-100">{{ number_format($totalChickens) }}</p>
                <p class="text-xs text-gray-500 mt-1">Desde o início da operação</p>
            </div>

            <div class="card p-4 sm:p-5 animate-slide-up" style="animation-delay:0.08s">
                <div class="flex items-center gap-2 mb-1">
                    <i class="fas fa-calendar-day text-emerald-400 text-sm"></i>
                    <span class="text-xs font-medium text-gray-400 uppercase tracking-wide">Produção Hoje</span>
                </div>
                <p id="todayChickens" class="kpi-value text-gray-100">{{ number_format($todayChickens) }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ now()->format('d/m/Y') }}</p>
            </div>

            <div class="card p-4 sm:p-5 animate-slide-up" style="animation-delay:0.16s">
                <div class="flex items-center gap-2 mb-1">
                    <i class="fas fa-gauge text-violet-400 text-sm"></i>
                    <span class="text-xs font-medium text-gray-400 uppercase tracking-wide">Taxa Média</span>
                </div>
                <p id="productionRate" class="kpi-value text-gray-100">{{ number_format($productionRate, 1) }}</p>
                <p class="text-xs text-gray-500 mt-1">Frangos / minuto (últ. 5 min)</p>
            </div>

            <div class="card p-4 sm:p-5 animate-slide-up" style="animation-delay:0.24s">
                <div class="flex items-center gap-2 mb-1">
                    <i class="fas fa-bell text-amber-400 text-sm"></i>
                    <span class="text-xs font-medium text-gray-400 uppercase tracking-wide">Alertas</span>
                </div>
                <p id="activeAlertsCount" class="kpi-value {{ $activeAlerts->count() > 0 ? 'text-red-400' : 'text-gray-600' }}">{{ $activeAlerts->count() }}</p>
                <p class="text-xs text-gray-500 mt-1">Pendentes de resolução</p>
            </div>
        </div>

        <!-- Main Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            <!-- Sensor Readings -->
            <div class="lg:col-span-2 card animate-slide-up" style="animation-delay:0.1s">
                <div class="px-4 sm:px-5 py-4 border-b border-[#334155] flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-gray-200 flex items-center gap-2">
                        <i class="fas fa-list text-gray-500 text-xs"></i>
                        Últimas Leituras do Sensor
                    </h2>
                    <span class="text-xs text-gray-500">{{ $recentRecords->count() }} registro(s)</span>
                </div>
                <div class="overflow-x-auto scrollbar-thin">
                    @if($recentRecords->isEmpty())
                    <div class="flex flex-col items-center justify-center py-16 text-gray-600">
                        <i class="fas fa-chart-line text-4xl mb-3"></i>
                        <p class="text-sm text-gray-500">Nenhuma leitura registrada</p>
                        <p class="text-xs text-gray-600 mt-1">Utilize o botão "Simular Detecção" para gerar dados</p>
                    </div>
                    @else
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-500 border-b border-[#334155]">
                                <th class="py-3 px-4 sm:px-5 font-medium text-xs uppercase tracking-wide">Frangos</th>
                                <th class="py-3 px-4 sm:px-5 font-medium text-xs uppercase tracking-wide hidden sm:table-cell">Sensor</th>
                                <th class="py-3 px-4 sm:px-5 font-medium text-xs uppercase tracking-wide">Data / Hora</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentRecords as $i => $record)
                            <tr class="border-b border-[#334155]/50 hover:bg-[#334155]/30 transition-colors">
                                <td class="py-3 px-4 sm:px-5">
                                    <span class="inline-flex items-center gap-1 text-emerald-400 bg-emerald-500/10 px-2 py-0.5 rounded-md text-xs font-medium">
                                        <i class="fas fa-plus text-[10px]"></i>
                                        {{ $record->chicken_count }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 sm:px-5 text-gray-400 hidden sm:table-cell">
                                    <span class="inline-flex items-center gap-1.5 text-xs">
                                        <i class="fas fa-microchip text-gray-500"></i>
                                        HC-SR04
                                    </span>
                                </td>
                                <td class="py-3 px-4 sm:px-5 text-gray-400 text-xs font-mono">{{ $record->detected_at->format('d/m/Y H:i:s') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>

            <!-- Alerts -->
            <div class="card animate-slide-up" style="animation-delay:0.2s">
                <div class="px-4 sm:px-5 py-4 border-b border-[#334155] flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-gray-200 flex items-center gap-2">
                        <i class="fas fa-bell text-gray-500 text-xs"></i>
                        Alertas Ativos
                    </h2>
                    <span class="text-xs text-gray-500">{{ $activeAlerts->count() }} pendente(s)</span>
                </div>
                <div id="alertasLista" class="p-4 sm:p-5 space-y-2 max-h-80 overflow-y-auto scrollbar-thin">
                    @forelse($activeAlerts as $alert)
                    <div class="flex items-start gap-3 bg-red-500/5 border border-red-500/20 rounded-lg p-3">
                        <div class="w-7 h-7 bg-red-500/20 rounded-lg flex items-center justify-center text-red-400 shrink-0">
                            <i class="fas fa-exclamation text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-red-300">{{ $alert->message }}</p>
                            <p class="text-xs text-red-500/70 mt-0.5">{{ $alert->created_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                        <button onclick="resolverAlerta({{ $alert->id }})" class="shrink-0 w-7 h-7 bg-[#334155] border border-[#475569] rounded-lg text-gray-400 hover:text-emerald-400 hover:border-emerald-500/50 transition-colors flex items-center justify-center" title="Resolver alerta">
                            <i class="fas fa-check text-xs"></i>
                        </button>
                    </div>
                    @empty
                    <div class="flex flex-col items-center justify-center py-10 text-gray-600">
                        <i class="fas fa-shield-check text-3xl mb-2"></i>
                        <p class="text-sm text-gray-500">Nenhum alerta ativo</p>
                        <p class="text-xs text-gray-600 mt-0.5">Linha operando normalmente</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- Flow Diagram -->
        <div class="card animate-slide-up" style="animation-delay:0.3s">
            <div class="px-4 sm:px-5 py-4 border-b border-[#334155]">
                <h2 class="text-sm font-semibold text-gray-200 flex items-center gap-2">
                    <i class="fas fa-diagram-project text-gray-500 text-xs"></i>
                    Arquitetura do Sistema
                </h2>
            </div>
            <div class="p-4 sm:p-5">
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 sm:gap-0">
                    <div class="flex flex-col items-center text-center px-4">
                        <div class="w-14 h-14 bg-blue-500/10 border border-blue-500/30 rounded-xl flex items-center justify-center mb-2">
                            <i class="fas fa-microchip text-blue-400 text-xl"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-200">Sensor HC-SR04</p>
                        <p class="text-xs text-gray-500">Ultrassônico</p>
                    </div>
                    <div class="hidden sm:block text-gray-600 text-xl">→</div>
                    <div class="sm:hidden text-gray-600"><i class="fas fa-arrow-down"></i></div>
                    <div class="flex flex-col items-center text-center px-4">
                        <div class="w-14 h-14 bg-indigo-500/10 border border-indigo-500/30 rounded-xl flex items-center justify-center mb-2">
                            <i class="fas fa-microchip text-indigo-400 text-xl"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-200">Arduino Uno</p>
                        <p class="text-xs text-gray-500">Microcontrolador</p>
                    </div>
                    <div class="hidden sm:block text-gray-600 text-xl">→</div>
                    <div class="sm:hidden text-gray-600"><i class="fas fa-arrow-down"></i></div>
                    <div class="flex flex-col items-center text-center px-4">
                        <div class="w-14 h-14 bg-emerald-500/10 border border-emerald-500/30 rounded-xl flex items-center justify-center mb-2">
                            <i class="fas fa-wifi text-emerald-400 text-xl"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-200">Wi-Fi</p>
                        <p class="text-xs text-gray-500">Comunicação</p>
                    </div>
                    <div class="hidden sm:block text-gray-600 text-xl">→</div>
                    <div class="sm:hidden text-gray-600"><i class="fas fa-arrow-down"></i></div>
                    <div class="flex flex-col items-center text-center px-4">
                        <div class="w-14 h-14 bg-violet-500/10 border border-violet-500/30 rounded-xl flex items-center justify-center mb-2">
                            <i class="fas fa-gauge text-violet-400 text-xl"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-200">Dashboard</p>
                        <p class="text-xs text-gray-500">Este painel</p>
                    </div>
                </div>
                <div class="mt-4 bg-[#1e293b] rounded-lg p-3 border border-[#334155]">
                    <div class="flex items-start gap-3 text-xs text-gray-400">
                        <i class="fas fa-circle-info text-gray-500 mt-0.5"></i>
                        <p>Fluxo: o sensor detecta a passagem do frango na esteira → Arduino processa o sinal → envia via Wi-Fi → dashboard exibe em tempo real.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center text-xs text-gray-600 py-4 border-t border-[#334155]">
            <p>Posto 2 — Limpeza Externa &bull; SENAI &bull; Atividade Final IoT &bull; {{ date('Y') }}</p>
        </div>

    </main>

    <script>
        let toastId = 0;

        function clock() {
            const now = new Date();
            document.getElementById('clock').textContent = now.toLocaleTimeString('pt-BR');
        }
        clock();
        setInterval(clock, 1000);

        function toast(tipo, titulo, msg) {
            const c = document.getElementById('toastContainer');
            const id = ++toastId;
            const cfg = {
                success: { icon: 'fa-check-circle', color: 'text-emerald-400', border: 'border-emerald-500/30', bg: 'bg-emerald-500/10' },
                warning: { icon: 'fa-exclamation-circle', color: 'text-amber-400', border: 'border-amber-500/30', bg: 'bg-amber-500/10' },
                error: { icon: 'fa-times-circle', color: 'text-red-400', border: 'border-red-500/30', bg: 'bg-red-500/10' },
            };
            const s = cfg[tipo] || cfg.info;
            const el = document.createElement('div');
            el.id = 't' + id;
            el.className = `animate-toast-in pointer-events-auto ${s.bg} border ${s.border} rounded-lg p-4 shadow-2xl flex items-start gap-3 backdrop-blur-md`;
            el.innerHTML = `
                <i class="fas ${s.icon} ${s.color} text-sm mt-0.5"></i>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-100">${titulo}</p>
                    <p class="text-xs text-gray-400 mt-0.5">${msg}</p>
                </div>
                <button onclick="fc(${id})" class="text-gray-500 hover:text-gray-300"><i class="fas fa-times text-xs"></i></button>
            `;
            c.appendChild(el);
            setTimeout(() => fc(id), 5000);
        }

        function fc(id) {
            const el = document.getElementById('t' + id);
            if (!el) return;
            el.classList.remove('animate-toast-in');
            el.classList.add('animate-toast-out');
            setTimeout(() => el.remove(), 250);
        }

        function att() {
            fetch(window.location.href, { headers: { 'Accept': 'text/html' } })
                .then(r => r.text())
                .then(h => {
                    const d = new DOMParser().parseFromString(h, 'text/html');
                    document.getElementById('totalChickens').textContent = d.getElementById('totalChickens').textContent;
                    document.getElementById('todayChickens').textContent = d.getElementById('todayChickens').textContent;
                    document.getElementById('productionRate').textContent = d.getElementById('productionRate').textContent;
                    const ac = document.getElementById('activeAlertsCount');
                    const na = d.getElementById('activeAlertsCount');
                    ac.textContent = na.textContent;
                    ac.className = na.className;
                    document.getElementById('alertasLista').innerHTML = d.getElementById('alertasLista').innerHTML;
                    const src = document.querySelector('.lg\\:col-span-2');
                    const dst = d.querySelector('.lg\\:col-span-2');
                    if (src && dst) src.querySelector('.overflow-x-auto').innerHTML = dst.querySelector('.overflow-x-auto').innerHTML;
                });
        }
        setInterval(att, 10000);

        document.getElementById('btnSimular').addEventListener('click', function() {
            const b = this; b.disabled = true; const t = b.innerHTML;
            b.innerHTML = '<i class="fas fa-spinner fa-spin text-xs"></i> Aguarde...';
            fetch('/simulacao/detectar', { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } })
                .then(r => r.json()).then(d => { toast('success', 'Detecção registrada', d.chickens + ' frango(s) detectado(s). Total: ' + d.total + '.'); att(); })
                .finally(() => { b.disabled = false; b.innerHTML = t; });
        });

        document.getElementById('btnAlerta').addEventListener('click', function() {
            const b = this; b.disabled = true; const t = b.innerHTML;
            b.innerHTML = '<i class="fas fa-spinner fa-spin text-xs"></i> Aguarde...';
            fetch('/simulacao/alerta', { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } })
                .then(r => r.json()).then(d => { toast('warning', 'Alerta gerado', d.alert.message); att(); })
                .finally(() => { b.disabled = false; b.innerHTML = t; });
        });

        function resolverAlerta(id) {
            fetch('/simulacao/alerta/' + id + '/resolver', { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } })
                .then(r => r.json()).then(() => { toast('success', 'Alerta resolvido', 'O alerta foi encerrado.'); att(); });
        }
    </script>
</body>
</html>