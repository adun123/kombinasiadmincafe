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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->string('invoice')->unique();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('table_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();

            $table->enum('type', ['dine-in', 'takeaway', 'delivery'])->default('takeaway');

            $table->decimal('subtotal', 12, 2);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('service_charge', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('total', 12, 2);


            $table->enum('payment_method', ['cash', 'qris', 'transfer', 'credit_card']);

            $table->decimal('paid_amount', 12, 2)->nullable();
            $table->decimal('change_amount', 12, 2)->nullable();

            $table->enum('status', ['pending', 'paid', 'cancelled'])->nullable();
            $table->boolean('sysnced')->default(false);
            $table->timestamps();


            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('table_id')->references('id')->on('tables');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
