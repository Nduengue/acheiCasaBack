<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /* 
        Modelo
        Table messages {
            id serial [primary key]
            chat_id int [ref: > open_chat.id]
            sender_id int [ref: > user.id, note: "pessoa que vai receber"]
            content text
            sent_in timestamp [default: "CURRENT_TIMESTAMP"]
        }
        */
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_id')->constrained('open_chats')->onDelete('cascade');
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade')->comment('pessoa que vai receber');
            $table->text('content');
            $table->timestamp('sent_in')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->boolean('read')->default(false)->comment('se a mensagem foi lida ou não');
            $table->boolean('deleted')->default(false)->comment('se a mensagem foi apagada ou não');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
