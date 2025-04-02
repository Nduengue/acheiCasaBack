<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Table intermediary {
            id serial [primary key]
            agency_id int [ref: > agency.id]
            user_id int [ref: > user.id]
            accept bool [default: false,note: "Aceitar ser intermediário"]
            deleted bool [default: false]
        }
        */
        Schema::create('intermediaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('accept')->default(false)->comment('Aceitar ser intermediário');
            $table->boolean('deleted')->default(false)->comment('se o intermediário foi apagado ou não');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intermediaries');
    }
};
