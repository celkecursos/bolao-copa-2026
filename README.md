# Bolão Copa do Mundo 2026

Sistema de bolão de palpites para a Copa do Mundo 2026. Cada usuário registrado faz seus palpites para os jogos, e o sistema calcula a pontuação automaticamente com base nos resultados reais importados da API [football-data.org](https://www.football-data.org).

Desenvolvido com **Laravel 13**, **PHP 8.3**, **MySQL 8**, **Tailwind CSS** e tema claro/escuro. Interface em português (pt-BR).

---

## Funcionalidades

- Cadastro e login de usuários
- Palpites por jogo com trava automática antes do início da partida
- Pontuação calculada automaticamente em segundo plano (fila)
- Ranking com desempate e Top 10 no dashboard
- Sincronização de jogos e resultados reais via API football-data.org
- Painel administrativo: gerenciar times, jogos e lançar resultados
- Auditoria de alterações nos jogos
- Notificações por e-mail: lembrete de palpite e aviso de resultado
- Tema claro/escuro com alternância persistente

---

## Requisitos

Antes de instalar, verifique se tem instalado:

| Ferramenta | Versão mínima | Verificar |
|---|---|---|
| PHP | 8.3 | `php -v` |
| Composer | 2.x | `composer --version` |
| Node.js | 24 | `node -v` |
| npm | 10+ | `npm -v` |
| MySQL | 8.0 | `mysql --version` |

---

## Instalação (desenvolvimento local)

### 1. Clonar o repositório

```bash
git clone https://github.com/celkecursos/bolao-copa-2026.git
cd bolao-copa-2026
```

### 2. Instalar dependências

```bash
composer install
npm install
```

### 3. Configurar o ambiente

Copie o arquivo de exemplo e abra para edição:

```bash
cp .env.example .env
```

Edite o `.env` com os dados do seu ambiente local. As seções importantes são:

```env
# Banco de dados (MySQL local)
DB_DATABASE=copa
DB_USERNAME=root
DB_PASSWORD=sua_senha_aqui

# Super-admin criado automaticamente pelo seeder
SEED_SUPERADMIN_NAME="Super Admin"
SEED_SUPERADMIN_EMAIL=admin@exemplo.com
SEED_SUPERADMIN_PASSWORD=SuaSenhaForte#1

# Token da API football-data.org (obtenha em https://www.football-data.org)
FOOTBALL_DATA_TOKEN=seu_token_aqui
```

> **Atenção:** senhas que contêm o caractere `#` precisam estar entre aspas duplas no `.env`,
> caso contrário o `#` é interpretado como comentário e a senha fica incompleta.
> Exemplo correto: `DB_PASSWORD="minhasenha#123"`

### 4. Criar o banco de dados

Crie o banco de dados no MySQL antes de rodar as migrations:

```sql
CREATE DATABASE copa CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Para os testes automatizados, crie também o banco de testes:

```sql
CREATE DATABASE copa_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 5. Gerar a chave da aplicação e popular o banco

```bash
php artisan key:generate
php artisan migrate:fresh --seed
```

O seed cria automaticamente:
- Roles e permissões (super-admin, admin, user)
- O super-admin com as credenciais do `.env`
- 48 seleções e 72 jogos da Copa 2026 (dados fictícios para desenvolvimento)
- 12 usuários demo com palpites e pontuação calculada

> Para usar **jogos reais da API** em vez dos fictícios, execute após o seed:
> ```bash
> php artisan worldcup:sync-matches
> ```

### 6. Gerar os assets e iniciar o servidor

Em terminais separados:

```bash
# Terminal 1 — servidor web
php artisan serve

# Terminal 2 — compilação dos assets (modo desenvolvimento com hot-reload)
npm run dev

# Terminal 3 — worker de filas (pontuação e e-mails)
php artisan queue:work
```

Ou rode tudo de uma vez com o atalho do Composer:

```bash
composer dev
```

Acesse: **http://127.0.0.1:8000**

Faça login com o e-mail e senha configurados em `SEED_SUPERADMIN_EMAIL` / `SEED_SUPERADMIN_PASSWORD` no `.env`.

---

## Papéis e permissões

| Papel | O que pode fazer |
|---|---|
| **super-admin** | Acesso total — passa por cima de todas as permissões |
| **admin** | Gerenciar times e jogos, lançar resultados, criar palpites |
| **user** | Fazer palpites e visualizar o ranking |

---

## Sistema de pontuação

A pontuação é calculada automaticamente quando um resultado é lançado. Apenas a regra de maior valor se aplica (não acumula):

| Acerto | Pontos |
|---|---|
| Placar exato (ex: 2 x 1 = 2 x 1) | 10 |
| Vencedor ou empate correto (ex: vitória do time A) | 5 |
| Placar parcial (um dos times acertado) | 1 |
| Nenhum acerto | 0 |

As regras são configuráveis em `config/bolao.php`.

---

## Comandos úteis

```bash
# Importar/atualizar jogos e resultados reais da API
php artisan worldcup:sync-matches

# Enviar e-mail de lembrete para quem ainda não palpitou
php artisan matches:send-reminders

# Executar o agendador manualmente (cron)
php artisan schedule:run

# Ver todas as tarefas agendadas
php artisan schedule:list

# Rodar os testes automatizados (Pest)
php artisan test

# Verificar estilo de código (Laravel Pint)
./vendor/bin/pint --test
```

---

## Testes

Os testes usam **MySQL** (banco `copa_test`) — não SQLite. Certifique-se de que o banco `copa_test` existe antes de rodar:

```bash
php artisan test
```

---

## Deploy em produção

Consulte as tasks de deploy na pasta `.claude/tasks/`:

- `task-11-deploy-vps-ssh.txt` — preparar a VPS e conexões SSH
- `task-12-deploy-app.txt` — clonar, configurar `.env` de produção e popular o banco com dados reais
- `task-13-deploy-cron-jobs.txt` — cron, filas com Supervisor e rotina de atualização

Em produção, o seed de dados usa `ProductionSeeder` (importa jogos reais da API):

```bash
php artisan db:seed --class=ProductionSeeder --force
```

---

## Estrutura resumida

```
app/
  Console/Commands/     — worldcup:sync-matches, matches:send-reminders
  Http/Controllers/     — BetController, GameController, RankingController...
  Jobs/                 — RecalculateGamePointsJob
  Models/               — User, Game, Team, Bet
  Notifications/        — BetReminderNotification, ResultPostedNotification
  Services/             — FootballDataService, ScoringService, RankingService
config/
  bolao.php             — regras de pontuação e configurações do bolão
database/
  migrations/           — estrutura das tabelas
  seeders/
    DatabaseSeeder.php      — seeder de desenvolvimento (jogos fictícios)
    ProductionSeeder.php    — seeder de produção (jogos reais da API)
```

---

## Autor

Desenvolvido por [Cesar Szpak](https://celke.com.br) — [Celke Cursos](https://github.com/celkecursos).

## Licença

MIT — veja o arquivo [LICENSE](LICENSE.txt) para detalhes.
