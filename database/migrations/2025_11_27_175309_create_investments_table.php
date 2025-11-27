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
        Schema::create('investments', function (Blueprint $table) {
            $table->id();
            $table->string('uid');
            $table->date('start_date');
            $table->decimal('capital_amount', 15, 2);
            $table->string('status');
            $table->foreignId('fund_id')->constrained('funds')->onDelete('cascade');
            $table->foreignId('investor_id')->constrained('investors')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investments');
    }
};