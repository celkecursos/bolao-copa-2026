<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\Team;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;

class WorldCup2026Seeder extends Seeder
{
    /**
     * 12 grupos (A–L), 4 seleções cada = 48 times.
     * Ajuste nomes/siglas/grupos conforme a tabela oficial / repescagens.
     */
    private array $groups = [
        'A' => [
            ['name' => 'México', 'code' => 'MEX', 'flag' => '🇲🇽'],
            ['name' => 'Croácia', 'code' => 'CRO', 'flag' => '🇭🇷'],
            ['name' => 'Arábia Saudita', 'code' => 'KSA', 'flag' => '🇸🇦'],
            ['name' => 'Camarões', 'code' => 'CMR', 'flag' => '🇨🇲'],
        ],
        'B' => [
            ['name' => 'Canadá', 'code' => 'CAN', 'flag' => '🇨🇦'],
            ['name' => 'Bélgica', 'code' => 'BEL', 'flag' => '🇧🇪'],
            ['name' => 'Costa do Marfim', 'code' => 'CIV', 'flag' => '🇨🇮'],
            ['name' => 'Catar', 'code' => 'QAT', 'flag' => '🇶🇦'],
        ],
        'C' => [
            ['name' => 'Estados Unidos', 'code' => 'USA', 'flag' => '🇺🇸'],
            ['name' => 'Holanda', 'code' => 'NED', 'flag' => '🇳🇱'],
            ['name' => 'Egito', 'code' => 'EGY', 'flag' => '🇪🇬'],
            ['name' => 'Costa Rica', 'code' => 'CRC', 'flag' => '🇨🇷'],
        ],
        'D' => [
            ['name' => 'Argentina', 'code' => 'ARG', 'flag' => '🇦🇷'],
            ['name' => 'Noruega', 'code' => 'NOR', 'flag' => '🇳🇴'],
            ['name' => 'Austrália', 'code' => 'AUS', 'flag' => '🇦🇺'],
            ['name' => 'Panamá', 'code' => 'PAN', 'flag' => '🇵🇦'],
        ],
        'E' => [
            ['name' => 'França', 'code' => 'FRA', 'flag' => '🇫🇷'],
            ['name' => 'Senegal', 'code' => 'SEN', 'flag' => '🇸🇳'],
            ['name' => 'Japão', 'code' => 'JPN', 'flag' => '🇯🇵'],
            ['name' => 'Nova Zelândia', 'code' => 'NZL', 'flag' => '🇳🇿'],
        ],
        'F' => [
            ['name' => 'Brasil', 'code' => 'BRA', 'flag' => '🇧🇷'],
            ['name' => 'Marrocos', 'code' => 'MAR', 'flag' => '🇲🇦'],
            ['name' => 'Coreia do Sul', 'code' => 'KOR', 'flag' => '🇰🇷'],
            ['name' => 'Jordânia', 'code' => 'JOR', 'flag' => '🇯🇴'],
        ],
        'G' => [
            ['name' => 'Inglaterra', 'code' => 'ENG', 'flag' => '🏴'],
            ['name' => 'Uruguai', 'code' => 'URU', 'flag' => '🇺🇾'],
            ['name' => 'Irã', 'code' => 'IRN', 'flag' => '🇮🇷'],
            ['name' => 'Uzbequistão', 'code' => 'UZB', 'flag' => '🇺🇿'],
        ],
        'H' => [
            ['name' => 'Espanha', 'code' => 'ESP', 'flag' => '🇪🇸'],
            ['name' => 'Colômbia', 'code' => 'COL', 'flag' => '🇨🇴'],
            ['name' => 'Nigéria', 'code' => 'NGA', 'flag' => '🇳🇬'],
            ['name' => 'Honduras', 'code' => 'HON', 'flag' => '🇭🇳'],
        ],
        'I' => [
            ['name' => 'Portugal', 'code' => 'POR', 'flag' => '🇵🇹'],
            ['name' => 'Suíça', 'code' => 'SUI', 'flag' => '🇨🇭'],
            ['name' => 'Equador', 'code' => 'ECU', 'flag' => '🇪🇨'],
            ['name' => 'Gana', 'code' => 'GHA', 'flag' => '🇬🇭'],
        ],
        'J' => [
            ['name' => 'Alemanha', 'code' => 'GER', 'flag' => '🇩🇪'],
            ['name' => 'Dinamarca', 'code' => 'DEN', 'flag' => '🇩🇰'],
            ['name' => 'Tunísia', 'code' => 'TUN', 'flag' => '🇹🇳'],
            ['name' => 'Cabo Verde', 'code' => 'CPV', 'flag' => '🇨🇻'],
        ],
        'K' => [
            ['name' => 'Áustria', 'code' => 'AUT', 'flag' => '🇦🇹'],
            ['name' => 'Paraguai', 'code' => 'PAR', 'flag' => '🇵🇾'],
            ['name' => 'Argélia', 'code' => 'ALG', 'flag' => '🇩🇿'],
            ['name' => 'Escócia', 'code' => 'SCO', 'flag' => '🏴'],
        ],
        'L' => [
            ['name' => 'Sérvia', 'code' => 'SRB', 'flag' => '🇷🇸'],
            ['name' => 'Turquia', 'code' => 'TUR', 'flag' => '🇹🇷'],
            ['name' => 'África do Sul', 'code' => 'RSA', 'flag' => '🇿🇦'],
            ['name' => 'Polônia', 'code' => 'POL', 'flag' => '🇵🇱'],
        ],
    ];

    public function run(): void
    {
        // Confrontos round-robin de 4 times (índices 0–3): 6 jogos por grupo.
        $fixtures = [[0, 1], [2, 3], [0, 2], [3, 1], [3, 0], [1, 2]];

        // Início da fase de grupos (UTC). Jogos espalhados em junho/2026.
        $kickoff = CarbonImmutable::parse('2026-06-11 13:00:00', 'UTC');
        $i = 0;

        foreach ($this->groups as $letter => $teams) {
            $created = [];
            foreach ($teams as $data) {
                $created[] = Team::create([
                    'name' => $data['name'],
                    'code' => $data['code'],
                    'group' => $letter,
                    'flag' => $data['flag'],
                ]);
            }

            foreach ($fixtures as [$home, $away]) {
                Game::create([
                    'home_team_id' => $created[$home]->id,
                    'away_team_id' => $created[$away]->id,
                    'group' => $letter,
                    'stage' => 'group',
                    // 4 jogos por dia (13h, 16h, 19h, 22h UTC).
                    'match_datetime' => $kickoff->addDays(intdiv($i, 4))->addHours(($i % 4) * 3),
                    'status' => 'scheduled',
                ]);
                $i++;
            }
        }
    }
}
