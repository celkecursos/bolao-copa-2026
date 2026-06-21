# Bolão da Copa do Mundo 2026

Sistema de bolão (palpites) da Copa 2026 — **Laravel 13 + Tailwind (Breeze)**, com login,
papéis/permissões (spatie), painel admin com auditoria, palpites com antifraude,
pontuação em fila, ranking e integração com a API football-data.org.
Interface em **português (pt-BR)** e **tema claro/escuro** com alternância persistente.

## Requisitos
- PHP 8.3 · Composer · Node.js 24 · MySQL 8

## Instalação (desenvolvimento)
```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
```
Configure o `.env` (banco, super-admin e token da API). Em seguida:
```bash
php artisan migrate:fresh --seed   # cria tabelas, papéis, 48 times, 72 jogos e dados demo
npm run build                      # ou: npm run dev
php artisan serve
php artisan queue:work             # processa pontuação e e-mails (fila database)
```
Acesse http://127.0.0.1:8000 e entre com o super-admin definido no `.env`
(`SEED_SUPERADMIN_EMAIL` / `SEED_SUPERADMIN_PASSWORD`).

## Papéis
- **super-admin** — acesso total (passa por cima de todas as permissões via `Gate::before`).
- **admin** — gerencia times/jogos e lança resultados.
- **user** — faz palpites e vê o ranking.

## Pontuação (configurável em `config/bolao.php`)
- Placar exato: **10** · Acertou o vencedor: **5** · Acerto parcial (um placar): **1** · Erro: **0**
  (não acumula — usa a maior regra aplicável).

## Funcionalidades
- **Palpites**: travam X min antes do início (`bet_lock_buffer_minutes`); palpites de
  terceiros só aparecem após o início; mata-mata não aceita empate.
- **Admin**: CRUD de times/jogos, lançamento de resultado (dispara `RecalculateGamePointsJob`)
  e **auditoria** (owen-it/laravel-auditing) com histórico por jogo.
- **Dashboard**: Top 10 do ranking (com desempate), jogos do dia e cards de resumo.
- **API football-data.org**: `php artisan worldcup:sync-matches` (agendado a cada 15 min)
  importa/atualiza times, jogos e **resultados reais** via `/v4/competitions/WC/matches`,
  respeitando o rate limit pelos headers da resposta. Jogos de mata-mata ainda sem times
  sorteados são ignorados (entram em sincronizações futuras).
- **E-mail (iAgente em produção)**: lembrete de palpite e aviso de resultado, via fila.

## Comandos úteis
```bash
php artisan worldcup:sync-matches    # importa jogos/resultados reais da API
php artisan matches:send-reminders   # lembra quem não palpitou
php artisan schedule:run             # executa o agendador
php artisan test                     # suíte de testes (Pest)
```

> Datas são salvas em **UTC** e exibidas em **horário de Brasília**.
