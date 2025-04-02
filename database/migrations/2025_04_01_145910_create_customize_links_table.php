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
            Table customize_link {
                id serial [primary key]
                agency_id int [ref: > agency.id, null, note: "pode dar um opcião para ser geral ou especifico se agencia"]
                intermediary_id int [ref: > intermediary.id, null, note: "pode dar um opcião para ser geral ou especifico intermerdiario"]
                user_id int [ref: > user.id, null, note: "pode dar um opcião para ser geral ou especifico usuário"]
                property_id int [ref: > property.id]
                link varchar [note: "Personalizar o link com taxa"]
                percent decimal [note: "% porcentagem comissão"]
                amount decimal [note: "Montante comissão"]
                deleted bool [default: false]
            }
        */
        Schema::create('customize_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_id')->nullable()->constrained()->onDelete('cascade')->comment('pode dar um opcião para ser geral ou especifico se agencia');
            $table->foreignId('intermediary_id')->nullable()->constrained()->onDelete('cascade')->comment('pode dar um opcião para ser geral ou especifico intermerdiario');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade')->comment('pode dar um opcião para ser geral ou especifico usuário');
            $table->foreignId('property_id')->constrained()->onDelete('cascade')->comment('propriedade');
            $table->string('link')->comment('Personalizar o link com taxa');
            $table->decimal('percent')->comment('% porcentagem comissão');
            $table->decimal('amount')->comment('Montante comissão');
            $table->boolean('deleted')->default(false)->comment('se o intermediário foi apagado ou não');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customize_links');
    }
};
