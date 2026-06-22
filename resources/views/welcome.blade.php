<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} — Bolão da Copa do Mundo 2026</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    @include('partials.theme-script')
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-[#F0FAF4] dark:bg-[#001233] text-gray-900 dark:text-gray-100">

<x-educational-banner />

{{-- ═══════════════════════════════════════════════════════
     NAVBAR
═══════════════════════════════════════════════════════ --}}
<header class="bg-[#009C3B] dark:bg-[#001a50] border-b border-[#007A2F] dark:border-[#003A8C] sticky top-0 z-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        {{-- Logo + nome --}}
        <a href="/" class="flex items-center gap-3 group">
            <x-application-logo class="h-9 w-9 shrink-0" />
            <span class="text-white font-bold text-lg leading-tight hidden sm:block group-hover:text-[#FFDF00] transition-colors">
                Bolão Copa 2026
            </span>
        </a>

        {{-- Ações --}}
        <div class="flex items-center gap-2">
            <x-theme-toggle />
            @auth
                <a href="{{ route('dashboard') }}"
                   class="px-4 py-1.5 bg-[#FFDF00] hover:bg-yellow-300 text-[#002776] font-semibold text-sm rounded-full transition-colors">
                    Ir ao Dashboard
                </a>
            @else
                @if (Route::has('login'))
                    <a href="{{ route('login') }}"
                       class="px-4 py-1.5 text-white/90 hover:text-[#FFDF00] font-medium text-sm transition-colors">
                        Entrar
                    </a>
                @endif
                @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                       class="px-4 py-1.5 bg-[#FFDF00] hover:bg-yellow-300 text-[#002776] font-semibold text-sm rounded-full transition-colors">
                        Cadastrar
                    </a>
                @endif
            @endauth
        </div>
    </div>
</header>

{{-- ═══════════════════════════════════════════════════════
     HERO
═══════════════════════════════════════════════════════ --}}
<section class="relative overflow-hidden bg-gradient-to-br from-[#009C3B] via-[#005C23] to-[#002776]">

    {{-- Decoração: losango da bandeira --}}
    <div class="absolute inset-0 flex items-center justify-center pointer-events-none select-none" aria-hidden="true">
        <div class="w-[700px] h-[450px] border-[60px] border-[#FFDF00]/8 rotate-0"
             style="clip-path: polygon(50% 0%, 100% 50%, 50% 100%, 0% 50%)"></div>
    </div>
    {{-- Círculo decorativo direita --}}
    <div class="absolute -right-24 top-1/2 -translate-y-1/2 w-96 h-96 rounded-full bg-[#FFDF00]/5 pointer-events-none" aria-hidden="true"></div>
    {{-- Círculo decorativo esquerda --}}
    <div class="absolute -left-16 -bottom-16 w-64 h-64 rounded-full bg-[#009C3B]/30 pointer-events-none" aria-hidden="true"></div>

    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-24 sm:py-32 text-center">
        {{-- Badge Copa --}}
        <div class="inline-flex items-center gap-2 bg-[#FFDF00]/15 border border-[#FFDF00]/40 text-[#FFDF00] text-sm font-semibold px-4 py-1.5 rounded-full mb-6">
            <span>🌍</span>
            <span>Copa do Mundo 2026 · EUA / Canadá / México</span>
        </div>

        {{-- Título principal --}}
        <h1 class="text-4xl sm:text-6xl font-extrabold text-white leading-tight mb-4">
            Bolão da
            <span class="text-[#FFDF00]">Copa 2026</span>
        </h1>
        <p class="text-lg sm:text-xl text-white/80 max-w-2xl mx-auto mb-10">
            Palpite no placar de cada jogo, acumule pontos e dispute o ranking com seus amigos — com resultados reais sincronizados automaticamente.
        </p>

        {{-- CTAs --}}
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            @auth
                <a href="{{ route('dashboard') }}"
                   class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-[#FFDF00] hover:bg-yellow-300 text-[#002776] font-bold text-base rounded-full shadow-lg transition-colors">
                    🏠 Ir ao Dashboard
                </a>
                <a href="{{ route('bets.index') }}"
                   class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-white/10 hover:bg-white/20 text-white font-semibold text-base rounded-full border border-white/30 transition-colors">
                    ⚽ Meus Palpites
                </a>
            @else
                @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-[#FFDF00] hover:bg-yellow-300 text-[#002776] font-bold text-base rounded-full shadow-lg transition-colors">
                        ⚽ Participar agora — é grátis
                    </a>
                @endif
                @if (Route::has('login'))
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-white/10 hover:bg-white/20 text-white font-semibold text-base rounded-full border border-white/30 transition-colors">
                        🔐 Já tenho conta
                    </a>
                @endif
            @endauth
        </div>

        {{-- Estatísticas rápidas --}}
        <div class="mt-14 grid grid-cols-3 gap-4 max-w-sm mx-auto">
            <div class="text-center">
                <div class="text-2xl font-extrabold text-[#FFDF00]">48</div>
                <div class="text-xs text-white/60 font-medium uppercase tracking-wide">Seleções</div>
            </div>
            <div class="text-center border-x border-white/20">
                <div class="text-2xl font-extrabold text-[#FFDF00]">104</div>
                <div class="text-xs text-white/60 font-medium uppercase tracking-wide">Jogos</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-extrabold text-[#FFDF00]">10</div>
                <div class="text-xs text-white/60 font-medium uppercase tracking-wide">Pts / acerto</div>
            </div>
        </div>
    </div>

    {{-- Onda divisória --}}
    <div class="absolute bottom-0 left-0 right-0 h-10 overflow-hidden" aria-hidden="true">
        <svg viewBox="0 0 1200 40" preserveAspectRatio="none" class="w-full h-full fill-[#F0FAF4] dark:fill-[#001233]">
            <path d="M0,40 C300,0 900,0 1200,40 L1200,40 L0,40 Z" />
        </svg>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     FEATURES
═══════════════════════════════════════════════════════ --}}
<section class="py-20 bg-[#F0FAF4] dark:bg-[#001233]">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-extrabold text-[#002776] dark:text-[#FFDF00]">Como funciona</h2>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Tudo o que você precisa para a Copa</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Feature 1 --}}
            <div class="br-card p-8 text-center hover:shadow-md transition-shadow">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-[#009C3B] dark:bg-[#009C3B]/30 mb-5">
                    <span class="text-3xl">🎯</span>
                </div>
                <h3 class="text-lg font-bold text-[#002776] dark:text-[#FFDF00] mb-2">Palpite no placar</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                    Preveja o placar exato de cada partida. O prazo fecha automaticamente <strong class="text-[#009C3B] dark:text-[#4DDB7A]">5 minutos</strong> antes do apito inicial.
                </p>
            </div>

            {{-- Feature 2 --}}
            <div class="br-card p-8 text-center hover:shadow-md transition-shadow border-t-4 border-[#FFDF00]">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-[#FFDF00] dark:bg-[#FFDF00]/20 mb-5">
                    <span class="text-3xl">🏆</span>
                </div>
                <h3 class="text-lg font-bold text-[#002776] dark:text-[#FFDF00] mb-2">Dispute o ranking</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                    Pontuação automática e ranking Top 10 atualizado a cada resultado. Desempate por maior quantidade de acertos exatos.
                </p>
            </div>

            {{-- Feature 3 --}}
            <div class="br-card p-8 text-center hover:shadow-md transition-shadow border-t-4 border-[#002776] dark:border-[#4DDB7A]">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-[#002776] dark:bg-[#002776]/50 mb-5">
                    <span class="text-3xl">⚡</span>
                </div>
                <h3 class="text-lg font-bold text-[#002776] dark:text-[#FFDF00] mb-2">Resultados reais</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                    Sincronização automática a cada 15 minutos com a API oficial <strong class="text-[#002776] dark:text-[#4DDB7A]">football-data.org</strong>. Sem precisar lançar manualmente.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     PONTUAÇÃO
═══════════════════════════════════════════════════════ --}}
<section class="py-20 bg-white dark:bg-[#001E40] border-y border-[#009C3B]/10 dark:border-[#003A8C]">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-extrabold text-[#002776] dark:text-[#FFDF00]">Sistema de pontuação</h2>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Vale sempre a <strong>maior</strong> categoria — as regras não acumulam</p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            {{-- 10 pts --}}
            <div class="relative bg-gradient-to-b from-[#009C3B] to-[#007A2F] rounded-2xl p-6 text-center text-white shadow-lg overflow-hidden">
                <div class="absolute inset-0 opacity-10" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255,255,255,.15) 10px, rgba(255,255,255,.15) 11px)"></div>
                <div class="relative text-5xl font-black text-[#FFDF00]">10</div>
                <div class="relative text-xs font-bold uppercase tracking-wider mt-1 text-white/80">pontos</div>
                <div class="relative mt-3 text-sm font-semibold">Placar exato ⭐</div>
                <div class="relative text-xs text-white/70 mt-1">Acertou os dois placares</div>
            </div>

            {{-- 5 pts --}}
            <div class="bg-[#E6F7EC] dark:bg-[#009C3B]/20 border-2 border-[#009C3B] rounded-2xl p-6 text-center">
                <div class="text-5xl font-black text-[#009C3B]">5</div>
                <div class="text-xs font-bold uppercase tracking-wider mt-1 text-[#009C3B]/70">pontos</div>
                <div class="mt-3 text-sm font-semibold text-[#002776] dark:text-[#FFDF00]">Vencedor certo</div>
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Acertou quem ganhou ou empate</div>
            </div>

            {{-- 1 pt --}}
            <div class="bg-[#E8F0FB] dark:bg-[#002776]/30 border-2 border-[#002776] dark:border-[#003A8C] rounded-2xl p-6 text-center">
                <div class="text-5xl font-black text-[#002776] dark:text-[#FFDF00]">1</div>
                <div class="text-xs font-bold uppercase tracking-wider mt-1 text-[#002776]/60 dark:text-[#FFDF00]/60">ponto</div>
                <div class="mt-3 text-sm font-semibold text-[#002776] dark:text-[#FFDF00]">Acerto parcial</div>
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Gols de um dos times</div>
            </div>

            {{-- 0 pts --}}
            <div class="bg-gray-100 dark:bg-[#001233] border-2 border-gray-200 dark:border-[#003A8C] rounded-2xl p-6 text-center">
                <div class="text-5xl font-black text-gray-400 dark:text-gray-600">0</div>
                <div class="text-xs font-bold uppercase tracking-wider mt-1 text-gray-400">pontos</div>
                <div class="mt-3 text-sm font-semibold text-gray-600 dark:text-gray-400">Errou</div>
                <div class="text-xs text-gray-500 dark:text-gray-500 mt-1">Sem acerto nenhum</div>
            </div>
        </div>

        <p class="text-center text-xs text-gray-500 dark:text-gray-400 mt-6">
            💡 Palpite <strong>5–0</strong> e resultado foi <strong>5–1</strong>? Vale 5 pts (acertou o vencedor), não acumula o 1 pt do placar parcial.
        </p>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     COMO PARTICIPAR — 4 PASSOS
═══════════════════════════════════════════════════════ --}}
<section class="py-20 bg-[#F0FAF4] dark:bg-[#001233]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-extrabold text-[#002776] dark:text-[#FFDF00]">Comece em 4 passos</h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ([
                ['num'=>'1','icon'=>'✍️','title'=>'Cadastre-se',    'desc'=>'Crie sua conta gratuitamente em menos de 1 minuto.'],
                ['num'=>'2','icon'=>'⚽','title'=>'Faça palpites',   'desc'=>'Preveja o placar de cada jogo antes do prazo fechar.'],
                ['num'=>'3','icon'=>'📺','title'=>'Acompanhe',       'desc'=>'Os resultados chegam automaticamente. Fique de olho no dashboard.'],
                ['num'=>'4','icon'=>'🏆','title'=>'Suba no ranking', 'desc'=>'Acumule pontos e apareça entre os 10 melhores!'],
            ] as $step)
            <div class="relative text-center">
                {{-- Linha conectora --}}
                @if (!$loop->last)
                <div class="hidden lg:block absolute top-8 left-1/2 w-full h-0.5 bg-[#009C3B]/20 dark:bg-[#FFDF00]/20 z-0"></div>
                @endif

                <div class="relative z-10">
                    {{-- Número --}}
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-[#009C3B] dark:bg-[#002776] border-4 border-[#FFDF00] text-white font-extrabold text-xl mb-4 shadow-lg">
                        {{ $step['num'] }}
                    </div>
                    <div class="text-2xl mb-2">{{ $step['icon'] }}</div>
                    <h3 class="font-bold text-[#002776] dark:text-[#FFDF00] mb-1">{{ $step['title'] }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">{{ $step['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     CTA FINAL
═══════════════════════════════════════════════════════ --}}
<section class="relative overflow-hidden bg-gradient-to-br from-[#002776] via-[#004A3F] to-[#009C3B] py-20">
    {{-- Decoração --}}
    <div class="absolute inset-0 flex items-center justify-center pointer-events-none" aria-hidden="true">
        <div class="w-[600px] h-[400px] border-[40px] border-[#FFDF00]/10"
             style="clip-path: polygon(50% 0%, 100% 50%, 50% 100%, 0% 50%)"></div>
    </div>

    <div class="relative max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <p class="text-[#FFDF00] font-bold text-sm uppercase tracking-widest mb-3">Pronto para a Copa?</p>
        <h2 class="text-3xl sm:text-4xl font-extrabold text-white mb-4">
            Entre no bolão e mostre que você <span class="text-[#FFDF00]">entende de futebol</span>
        </h2>
        <p class="text-white/70 mb-8">Cadastro gratuito · Resultados automáticos · Ranking ao vivo</p>

        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            @auth
                <a href="{{ route('dashboard') }}"
                   class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-[#FFDF00] hover:bg-yellow-300 text-[#002776] font-bold text-lg rounded-full shadow-xl transition-colors">
                    🏠 Acessar o bolão
                </a>
            @else
                @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-[#FFDF00] hover:bg-yellow-300 text-[#002776] font-bold text-lg rounded-full shadow-xl transition-colors">
                        ⚽ Criar minha conta
                    </a>
                @endif
                @if (Route::has('login'))
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-white/10 hover:bg-white/20 text-white font-semibold text-lg rounded-full border border-white/40 transition-colors">
                        🔐 Já tenho conta
                    </a>
                @endif
            @endauth
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     FOOTER
═══════════════════════════════════════════════════════ --}}
<footer class="bg-[#002776] dark:bg-[#000B1F] border-t-4 border-[#FFDF00]">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <x-application-logo class="h-8 w-8 shrink-0" />
            <div>
                <div class="text-white font-bold text-sm">Bolão Copa do Mundo 2026</div>
                <div class="text-white/50 text-xs">Resultados via football-data.org</div>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <span class="text-white/40 text-xs">© {{ date('Y') }}</span>
            <x-theme-toggle />
            @auth
                <a href="{{ route('dashboard') }}" class="text-[#FFDF00] hover:text-white text-xs font-medium transition-colors">Dashboard</a>
            @else
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="text-[#FFDF00] hover:text-white text-xs font-medium transition-colors">Entrar</a>
                @endif
            @endauth
        </div>
    </div>
</footer>

</body>
</html>
