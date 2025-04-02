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
        Table offer_option {
            id serial [primary key]
            property_id int [ref: > property.id]
            icon varchar [null]
            title varchar
            deleted bool [default: false]
        }
        */
        Schema::create('offer_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->onDelete('cascade');
            $table->string('icon')->nullable();
            $table->string('title');
            $table->boolean('deleted')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offer_options');
    }
};
