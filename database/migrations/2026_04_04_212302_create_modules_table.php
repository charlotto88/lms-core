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
            Schema::create('modules', function (Blueprint $table) {
                $table->id();
                $table->foreignId('chapter_id')->constrained()->cascadeOnDelete();
                $table->string('title');
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });

            // Point Materials to the Module instead of the Chapter
            Schema::table('course_materials', function (Blueprint $table) {
                // Drop the old chapter link if it exists, and add module_id
                $table->foreignId('module_id')->nullable()->constrained()->cascadeOnDelete();
            });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
