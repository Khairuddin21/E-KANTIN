<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();
            $table->bigInteger('amount');
            $table->string('bank_name');
            $table->string('account_number');
            $table->string('account_name');
            $table->enum('status', ['pending', 'approved', 'rejected', 'paid'])->default('pending');
            $table->text('admin_note')->nullable();
            $table->timestamps();

            $table->index(['seller_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('withdrawals');
    }
};
