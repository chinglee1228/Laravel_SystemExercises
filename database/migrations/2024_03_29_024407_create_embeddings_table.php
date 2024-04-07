<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\EmbedCollection;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
/*
        Schema::connection('pgsql')->create('embeddings', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->longText('text');
            //$table->foreignIdFor(EmbedCollection::class)->onDelete('cascade');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE embeddings ADD embedding vector;");*/
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('embeddings');
    }
};
