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
        Schema::table('courses', function (Blueprint $table) {
            // This adds the column and tells it to link to the 'categories' table
            $table->foreignId('category_id')
                ->nullable()           // Allows existing courses to stay 'Uncategorized' for now
                ->constrained()        // Automatically looks for the 'categories' table
                ->nullOnDelete();      // If you delete a Category, the Course stays but the ID becomes NULL
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['category_id']); // Drop the link first
            $table->dropColumn('category_id');    // Then drop the column
        });
    }
};
