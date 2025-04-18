<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /* Table checkpoints {
        id serial [primary key]
        user_id int [ref: > user.id]
        property_id int [ref: > property.id]
        check_in datatime 
        check_out datetime [not null, note: "opicional"]
        status enum("check_in","check_out","cancelled") [default: "check_in"]
    } */
    public function up(): void
    {
        Schema::create('check_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->dateTime('check_in');
            $table->dateTime('check_out')->nullable();
            $table->enum('status', ['check_in', 'check_out', 'cancelled'])->default('check_in');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('check_points');
    }
};
