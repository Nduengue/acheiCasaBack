<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /* 
        Table businesses {
            id serial [primary key]
            property_id int [ref: > property.id]
            buyer_id int [ref: > user.id, note: "quem quer comprar/alugar"]
            seller_id int [ref: > user.id, note: "dono do imóvel (ou agência)"]
            intermediary_id int [ref: > intermediary.id, null]
            price decimal
            status enum("pending", "accepted", "rejected", "closed", "cancelled") [default: "pending"]
            type_of_business enum("A", "V")  [note: "aluguel ou venda"]
            started_at timestamp 
            closed_at timestamp [null]
            notes text [null]
            deleted bool [default: false]
        }
    */
    public function up(): void
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('intermediary_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->decimal('price', 10, 2);
            $table->enum('status', ['pending', 'accepted', 'rejected', 'closed', 'cancelled'])->default('pending');
            $table->enum('type_of_business', ['A', 'V'])->comment('A: aluguel, V: venda');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('deleted')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
