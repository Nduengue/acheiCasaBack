<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   /*  Table payment_receipts {
        id serial [primary key]
        business_id int [ref: > businesses.id]
        user_id int [ref: > user.id, note: "quem enviou o comprovativo"] 
        path_receipt varchar [note: "caminho do comprovativo (imagem ou pdf)"]
        payment_method enum("bank_transfer", "cash", "other") [default: "bank_transfer"]
        sent_at timestamp 
        approved bool [default: false, note: "Aprovado manualmente?"]
        notes text [null]
    } */
    public function up(): void
    {
        Schema::create('payment_receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('path_receipt')->comment('caminho do comprovativo (imagem ou pdf)');
            $table->enum('payment_method', ['bank_transfer', 'cash', 'other'])->default('bank_transfer');
            $table->timestamp('sent_at')->nullable()->comment('data de envio do comprovativo');
            $table->boolean('approved')->default(false)->comment('Aprovado manualmente?');
            $table->text('notes')->nullable()->comment('Notas adicionais sobre o comprovativo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_receipts');
    }
};
