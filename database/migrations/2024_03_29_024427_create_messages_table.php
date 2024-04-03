<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    //chatbot對話紀錄
    public function up(): void
    {/*
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            //$table->foreignIdFor(Chat::class)->onDelete('cascade');
            $table->uuid('chat_id');
            $table->string("role");
            $table->text("content");
            $table->timestamps();
        });*/
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {/*
        Schema::dropIfExists('messages');*/
    }
};
