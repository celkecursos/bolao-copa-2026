<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 3)->nullable();          // sigla FIFA (ex.: BRA)
            $table->char('group', 1)->nullable();            // grupo A–L
            $table->string('flag')->nullable();              // url/emoji da bandeira
            $table->unsignedBigInteger('external_id')->nullable()->index(); // id na API football-data
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
