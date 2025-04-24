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
                category_id enum("Praia", "Reserva", "Loja", "Terreno", "Residencial", "Escritorio", "Quartos","Armazem")
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
                length decimal [null, note: "comprimento"]  
                width decimal [null, note: "largura"]
                description text
                room int [null]
                bathroom int [null]
                useful_sand decimal
                announces bool [default: false]
                favorite bool [default: false]
                deleted bool [default: false]
                time_unit enum("second","minute","hours","day","week","month","year") [null]
                minimum_time decimal [null]
                price decimal
            }
        */
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('category_id',["Praia", "Reserva", "Loja", "Terreno", "Residencial", "Escritorio", "Quartos","Armazem"])->default('other')->comment('Praia, Armazem, Loja, Terreno, Residencial, Escritorios, Quartos, ...');
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
            $table->decimal('length', 10, 2)->nullable()->comment('comprimento');
            $table->decimal('width', 10, 2)->nullable()->comment('largura');
            $table->text('description')->nullable();
            $table->integer('room')->nullable();
            $table->integer('bathroom')->nullable();
            $table->decimal('useful_sand', 10, 2)->nullable()->comment('Área útil');
            $table->boolean('deleted')->default(false);
            $table->boolean('favorite')->default(false)->comment('Favorito?');
            $table->boolean('announces')->default(false)->comment('Anunciar?');
            $table->enum('time_unit',["second","minute","hours","day","week","month","year"])->nullable(); // Ex: "dias", "semanas", "meses"
            $table->decimal('minimum_time')->nullable(); // Tempo mínimo permitido
            $table->decimal('price', 10, 2);
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
