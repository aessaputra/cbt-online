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
    Schema::create('course_questions', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->string('question');
      $table->foreignUuid('course_id')->constrained()->onDelete('cascade');
      $table->softDeletes();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('course_questions');
  }
};
