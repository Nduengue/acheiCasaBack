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
            Table agency {
                id serial [primary key]
                user_id int [ref: > user.id]
                name varchar
                path_photo varchar
                code varchar    [unique, null] 
                email varchar   [null]
                address varchar [null]
                phone varchar   [null]
            } 
        */
        Schema::create('agencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('code')->unique()->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('path_photo')->nullable();
            $table->boolean('deleted')->default(false); // active, inactive, suspended
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agencies');
    }
};
