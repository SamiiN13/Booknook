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
        Schema::table('books', function (Blueprint $table) {
            $table->integer('min_trust_score')->default(0)->after('status');
            $table->integer('max_loan_duration')->default(14)->comment('Maximum loan duration in days')->after('min_trust_score');
            $table->date('due_date')->nullable()->after('max_loan_duration');
            $table->enum('rarity', ['common', 'uncommon', 'rare', 'very_rare'])->default('common')->after('due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn(['min_trust_score', 'max_loan_duration', 'due_date', 'rarity']);
        });
    }
};
