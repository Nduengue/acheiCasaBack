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
            Table property {
                id serial [primary key]
                user_id int [ref: > user.id]
                title varchar
                type varchar [null, note: "Casa, Apartamento, Armazem, Loja, Terreno, ..."]
                status varchar [note: "usado,novo etc"]
                type_of_business enum("A","V") [note: "A - Alugar  V - Venda"]
                furnished  enum("yes","no") [note: "Mobilada? Não"]
                country varchar
                address varchar
                city varchar
                province varchar
                location array [null, note: "[latitude & longitude]"] 
                description text
                room int [null]
                bathroom int [null]
                price decimal
                deleted bool [default: false]
            } 
        */
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('type')->nullable()->comment('Casa, Apartamento, Armazem, Loja, Terreno, ...');
            $table->string('status')->nullable()->comment('usado,novo etc');
            $table->enum('type_of_business', ['A', 'V'])->comment('A - Alugar  V - Venda');
            $table->enum('furnished', ['yes', 'no'])->default('no')->comment('Mobilada? Não');
            $table->string('country');
            $table->string('address');
            $table->string('city');
            $table->string('province');
            $table->string('location')->nullable()->comment('[latitude & longitude]');
            $table->text('description')->nullable();
            $table->integer('room')->nullable();
            $table->integer('bathroom')->nullable();
            $table->decimal('price', 10, 2);
            $table->boolean('deleted')->default(false);
            $table->boolean('announces')->default(false)->comment('Anunciar?');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
