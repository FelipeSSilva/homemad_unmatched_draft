<div class="tab" id="statistics">
    <div id="stats-isolated-container">

        <!--
            Este bloco HTML/JavaScript é projetado para ser injetado (include)
            em um documento HTML existente.

            O CSS é estritamente isolado usando o ID #stats-isolated-container como prefixo
            para todos os seletores, minimizando conflitos com estilos globais.
        -->

        <style>
            /* ========================================================= */
            /* CSS ISOLADO - Todos os seletores devem começar com o ID: */
            /* ========================================================= */

            #stats-isolated-container {
                font-family: Arial, sans-serif;
                max-width: 900px;
                margin: 20px auto;
                background-color: #1f2937; /* Dark Gray - Base do fundo */
                color: #d1d5db; /* Light Gray - Texto principal */
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 10px 15px rgba(0, 0, 0, 0.3);
            }

            #stats-isolated-container h1 {
                font-size: 1.75rem;
                font-weight: bold;
                text-align: center;
                margin-bottom: 20px;
                color: #6366f1; /* Indigo - Título */
            }

            #stats-isolated-container h2 {
                font-size: 1.25rem;
                font-weight: 600;
                margin-top: 30px;
                margin-bottom: 15px;
                color: #e5e7eb; /* Off-White - Subtítulos */
            }

            /* Estilo da Tabela */
            #stats-isolated-container .isolated-table {
                width: 100%;
                border-collapse: separate;
                border-spacing: 0;
                overflow: hidden;
                border-radius: 6px;
            }

            /* Cabeçalho da Tabela */
            #stats-isolated-container .isolated-table thead tr {
                background-color: #374151; /* Darker Gray */
                color: #e5e7eb;
                font-size: 0.875rem;
                text-transform: uppercase;
            }

            #stats-isolated-container .isolated-table th,
            #stats-isolated-container .isolated-table td {
                padding: 12px 16px;
                text-align: left;
                border-bottom: 1px solid #334155; /* Slate for separator */
            }

            /* Corpo da Tabela */
            #stats-isolated-container .isolated-table tbody tr {
                background-color: #2c3846; /* Slightly lighter background for rows */
                transition: background-color 0.15s ease;
            }

            #stats-isolated-container .isolated-table tbody tr:hover {
                background-color: #374151; /* Highlight on hover */
            }

            /* Estilos específicos para texto */
            #stats-isolated-container .text-winrate {
                font-weight: bold;
            }

            /* Cores para Win Rate e Status */
            #stats-isolated-container .win-rate-high {
                color: #34d399; /* Green/Emerald */
            }
            #stats-isolated-container .win-rate-low {
                color: #f87171; /* Red/Rose */
            }

            /* Oculta Derrotas em telas muito pequenas (Mobile Responsiveness) */
            @media (max-width: 768px) {
                #stats-isolated-container .hidden-on-mobile {
                    display: none;
                }
            }
        </style>

        <div class="stats-content-area">
            <h1>
                Resumo de Estatísticas de Partidas
            </h1>

            <div id="stats-status-message" style="text-align: center; color: #9ca3af; padding: 15px;">
                <span id="stats-spinner">Carregando dados de partidas de data/UnMatches.json...</span>
            </div>

            <div id="stats-rankings-container" style="display: none;">

                <!-- Tabela de Jogadores -->
                <h2>1. Classificação por Jogador (Win Rate)</h2>
                <div id="stats-player-rankings"></div>

                <!-- Tabela de Heróis -->
                <h2>2. Classificação por Herói (Win Rate)</h2>
                <div id="stats-hero-rankings"></div>

                <!-- Tabela de Mapas (Apenas Frequência) -->
                <h2>3. Classificação por Mapa (Frequência)</h2>
                <div id="stats-map-rankings"></div>
            </div>
        </div>

        <script>
            // Caminho do arquivo JSON
            const FILE_PATH = 'data/UnMatches.json';

            // Elementos DOM (Usando IDs prefixados)
            const statusMessage = document.getElementById('stats-status-message');
            const rankingsContainer = document.getElementById('stats-rankings-container');


            // --- FUNÇÕES DE CÁLCULO DE ESTATÍSTICAS ---

            function calculatePlayerStats(data) {
                const playerStats = {};
                const updateStats = (playerName, isWinner) => {
                    if (!playerStats[playerName]) {
                        playerStats[playerName] = { wins: 0, losses: 0, total: 0, winRate: 0 };
                    }
                    playerStats[playerName].total++;
                    if (isWinner) {
                        playerStats[playerName].wins++;
                    } else {
                        playerStats[playerName].losses++;
                    }
                };
                data.forEach(match => {
                    updateStats(match.player_1.name, match.player_1.winner);
                    updateStats(match.player_2.name, match.player_2.winner);
                });
                const sortedPlayers = Object.keys(playerStats).map(name => {
                    const stats = playerStats[name];
                    stats.winRate = (stats.wins / stats.total) * 100 || 0;
                    return { name, ...stats };
                });
                sortedPlayers.sort((a, b) => {
                    if (b.winRate !== a.winRate) return b.winRate - a.winRate;
                    return b.total - a.total;
                });
                return sortedPlayers;
            }

            function calculateHeroStats(data) {
                const heroStats = {};
                const updateStats = (heroName, isWinner) => {
                    if (!heroStats[heroName]) {
                        heroStats[heroName] = { wins: 0, losses: 0, total: 0, winRate: 0 };
                    }
                    heroStats[heroName].total++;
                    if (isWinner) {
                        heroStats[heroName].wins++;
                    } else {
                        heroStats[heroName].losses++;
                    }
                };
                data.forEach(match => {
                    updateStats(match.player_1.hero, match.player_1.winner);
                    updateStats(match.player_2.hero, match.player_2.winner);
                });
                const sortedHeroes = Object.keys(heroStats).map(name => {
                    const stats = heroStats[name];
                    stats.winRate = (stats.wins / stats.total) * 100 || 0;
                    return { name, ...stats };
                });
                sortedHeroes.sort((a, b) => {
                    if (b.winRate !== a.winRate) return b.winRate - a.winRate;
                    return b.total - a.total;
                });
                return sortedHeroes;
            }

            function calculateMapStats(data) {
                const mapStats = {};
                data.forEach(match => {
                    const mapName = match.data.map;
                    if (!mapStats[mapName]) {
                        mapStats[mapName] = { total: 0, name: mapName };
                    }
                    mapStats[mapName].total++;
                });
                const sortedMaps = Object.values(mapStats);
                sortedMaps.sort((a, b) => b.total - a.total);
                return sortedMaps;
            }


            // --- FUNÇÕES DE RENDERIZAÇÃO DE TABELAS ---

            /**
             * Função genérica para renderizar tabelas de estatísticas com classes CSS isoladas.
             */
            function renderGenericTable(targetId, data, type) {
                const container = document.getElementById(targetId);

                if (data.length === 0) {
                    container.innerHTML = `<p style="color: #9ca3af; padding: 10px;">Nenhum dado de ${type.toLowerCase()} encontrado.</p>`;
                    return;
                }

                // Usando a classe 'isolated-table'
                let tableHTML = `<table class="isolated-table"><thead><tr>`;
                let tbodyHTML = `<tbody>`;

                if (type === 'MAPA') {
                    // Tabela de Mapa: Posição, Mapa, Frequência (Total)
                    tableHTML += `
                    <th>#</th>
                    <th>Mapa</th>
                    <th style="text-align: center;">Frequência (Partidas)</th>
                `;
                    data.forEach((entity, index) => {
                        tbodyHTML += `
                        <tr>
                            <td style="color: #6366f1; font-weight: bold;">${index + 1}</td>
                            <td>${entity.name}</td>
                            <td style="text-align: center;">${entity.total}</td>
                        </tr>
                    `;
                    });
                } else {
                    // Tabelas de Jogador e Herói: Posição, Nome, Total, Vitórias, Derrotas, Win Rate
                    const nameTitle = type === 'JOGADOR' ? 'Jogador' : 'Herói';

                    tableHTML += `
                    <th>#</th>
                    <th>${nameTitle}</th>
                    <th style="text-align: center;">Total</th>
                    <th style="text-align: center;">Vitórias</th>
                    <th class="hidden-on-mobile" style="text-align: center;">Derrotas</th>
                    <th style="text-align: center;">Win Rate (%)</th>
                `;
                    data.forEach((entity, index) => {
                        const winRateFormatted = entity.winRate.toFixed(2);
                        const winRateClass = entity.winRate >= 50 ? 'win-rate-high' : 'win-rate-low';

                        tbodyHTML += `
                        <tr>
                            <td style="color: #6366f1; font-weight: bold;">${index + 1}</td>
                            <td>${entity.name}</td>
                            <td style="text-align: center;">${entity.total}</td>
                            <td style="text-align: center;">${entity.wins}</td>
                            <td class="hidden-on-mobile" style="text-align: center;">${entity.losses}</td>
                            <td class="text-winrate ${winRateClass}" style="text-align: center;">
                                ${winRateFormatted}%
                            </td>
                        </tr>
                    `;
                    });
                }

                tableHTML += `</tr></thead>${tbodyHTML}</tbody></table>`;
                container.innerHTML = tableHTML;
            }


            // --- FUNÇÃO PRINCIPAL DE CARREGAMENTO ---

            async function loadAndProcessData() {
                const spinner = document.getElementById('stats-spinner');
                if (spinner) {
                    // Simples indicador de carregamento
                    spinner.innerHTML = `<span style="color: #6366f1; font-weight: bold;">⏳ Carregando...</span>`;
                }

                try {
                    const response = await fetch(FILE_PATH);

                    if (!response.ok) {
                        throw new Error(`Erro ao carregar o arquivo: ${response.status} ${response.statusText}`);
                    }

                    const matchesData = await response.json();

                    if (!Array.isArray(matchesData) || matchesData.length === 0) {
                        throw new Error("O arquivo JSON está vazio ou não é um array válido.");
                    }

                    const sortedPlayers = calculatePlayerStats(matchesData);
                    const sortedHeroes = calculateHeroStats(matchesData);
                    const sortedMaps = calculateMapStats(matchesData);

                    // Renderiza as tabelas nos contêineres prefixados
                    renderGenericTable('stats-player-rankings', sortedPlayers, 'JOGADOR');
                    renderGenericTable('stats-hero-rankings', sortedHeroes, 'HERÓI');
                    renderGenericTable('stats-map-rankings', sortedMaps, 'MAPA');

                    // Oculta o status e mostra as tabelas
                    statusMessage.style.display = 'none';
                    rankingsContainer.style.display = 'block';

                } catch (error) {
                    console.error("Erro no processamento de dados:", error);
                    // Mensagem de erro simples
                    statusMessage.innerHTML = `<span style="color: #f87171; font-weight: bold;">❌ Erro ao carregar ou processar os dados de partidas.</span><br><span style="font-size: 0.8rem; color: #9ca3af;">Detalhe: ${error.message}</span>`;
                    if (spinner) spinner.innerHTML = '';
                    rankingsContainer.style.display = 'none';
                }
            }

            // Inicia o carregamento quando o DOM estiver pronto
            document.addEventListener('DOMContentLoaded', loadAndProcessData);

        </script>
    </div>
</div>