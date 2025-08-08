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
        Schema::create('todos', function (Blueprint $table) {
            $table->id();
            //$table->bigInteger('id')->unsigned()->primary()->autoIncrement();
            $table->string('title', 255)->nullable(false);
            $table->text('description')->nullable();
            $table->enum('status', ['pending','in_progress','completed','cancelled'])->default('pending')->nullable();;
            $table->enum('priority', ['low','medium','high'])->default('medium')->nullable();;
            $table->date('due_date')->nullable(); // Deadline gibi son bitiş tarihi
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('todos');
    }
};
