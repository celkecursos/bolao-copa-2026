<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('home_team_id')->constrained('teams')->restrictOnDelete();
            $table->foreignId('away_team_id')->constrained('teams')->restrictOnDelete();
            $table->char('group', 1)->nullable(); // grupo A–L (nulo no mata-mata)
            $table->enum('stage', [
                'group', 'round_32', 'round_16', 'quarter', 'semi', 'third_place', 'final',
            ])->default('group');
            $table->dateTime('match_datetime'); // armazenado em UTC
            $table->string('stadium')->nullable();
            $table->string('city')->nullable();
            $table->unsignedTinyInteger('home_score')->nullable();
            $table->unsignedTinyInteger('away_score')->nullable();
            $table->enum('status', ['scheduled', 'live', 'finished'])->default('scheduled');
            $table->unsignedBigInteger('external_id')->nullable()->index(); // id da partida na API
            $table->timestamps();
            $table->softDeletes();

            $table->index(['stage', 'group']);
            $table->index('match_datetime');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
