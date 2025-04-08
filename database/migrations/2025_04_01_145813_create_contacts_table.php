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
            Table contact {
                id serial [primary key]
                agency_id int [ref: > agency.id, null]
                user_id int [ref: > user.id]
                property_id int [ref: > property.id]
                type enum("W","C","M") [note: "W - whatsapp C - contacto M - Mail"]
                value varchar [note: "número de telefone"] 
            } 
        */
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['W', 'C', 'M'])->comment('W - whatsapp C - contacto M - Mail');
            $table->string('value')->comment('número de telefone');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
